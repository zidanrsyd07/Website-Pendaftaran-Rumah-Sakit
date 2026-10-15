<?php

namespace App\Http\Controllers;

use App\Models\QueueTicket;
use App\Models\Registration;
use App\Services\SimrsServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(
        protected SimrsServiceInterface $simrs
    ) {}

    /**
     * Admin Dashboard — Full system overview.
     */
    public function admin()
    {
        $today = today();

        $totalToday = Registration::whereDate('visit_date', $today)->count();
        $serving = QueueTicket::whereDate('created_at', $today)->where('status', 'serving')->count();
        $completedToday = QueueTicket::whereDate('created_at', $today)->where('status', 'completed')->count();

        $avgWaitMinutes = QueueTicket::whereDate('created_at', $today)
            ->whereNotNull('served_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, served_at)) as avg_wait')
            ->value('avg_wait');

        $stats = [
            'total_registrations_today' => $totalToday,
            'serving_now' => $serving,
            'active_queues' => $serving,
            'total_patients' => Registration::count(),
            'completed_today' => $completedToday,
            'avg_wait_minutes' => $avgWaitMinutes ? round($avgWaitMinutes) : 0,
        ];

        // Status distribution for donut chart
        $statusDistribution = QueueTicket::whereDate('created_at', $today)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Hourly activity for bar chart (registrations per hour)
        $rawHourly = Registration::whereDate('visit_date', $today)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $hourlyActivity = [];
        for ($h = 8; $h <= 16; $h++) {
            $key = str_pad($h, 2, '0', STR_PAD_LEFT).':00';
            $hourlyActivity[$key] = $rawHourly[$h] ?? 0;
        }

        // Loket status monitoring
        $loketStatuses = $this->getLoketStatuses();

        // Recent registrations
        $recentRegistrations = Registration::with('queueTicket')
            ->whereDate('visit_date', $today)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Activity log (latest queue actions)
        $activityLog = QueueTicket::with(['registration', 'servedByUser'])
            ->whereDate('created_at', $today)
            ->whereIn('status', ['serving', 'completed', 'skipped'])
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();

        // Kanban board data
        $kanbanData = $this->getKanbanData();

        // SIMRS Bed statistics
        $bedStats = $this->simrs->getBedStatistics();

        return view('dashboard.admin', compact(
            'stats',
            'statusDistribution',
            'hourlyActivity',
            'loketStatuses',
            'recentRegistrations',
            'activityLog',
            'kanbanData',
            'bedStats'
        ));
    }

    /**
     * Loket Rawat Jalan Dashboard — Outpatient registration processing.
     */
    public function loketRawatJalan()
    {
        $today = today();
        $loketName = 'Loket Rawat Jalan';

        $totalToday = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_jalan')->count();
        $waitingVerification = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_jalan')->where('status', 'pending')->count();
        $verified = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_jalan')->where('status', 'verified')->count();
        $currentlyServing = QueueTicket::with('registration')
            ->where('loket_name', $loketName)
            ->whereDate('created_at', $today)
            ->where('status', 'serving')
            ->orderByDesc('served_at')
            ->first();

        $stats = [
            'total_today' => $totalToday,
            'waiting_verification' => $waitingVerification,
            'verified' => $verified,
            'current_queue' => $currentlyServing?->queue_number ?? '-',
            'completed_today' => Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_jalan')->where('status', 'completed')->count(),
            'waiting_queue' => QueueTicket::where('loket_name', $loketName)->whereDate('created_at', $today)->where('status', 'waiting')->count(),
        ];

        $registrations = Registration::with('queueTicket')
            ->whereDate('visit_date', $today)
            ->where('service_type', 'rawat_jalan')
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.loket-rawat-jalan', compact('stats', 'registrations', 'currentlyServing'));
    }

    /**
     * Loket Rawat Inap Dashboard — Inpatient registration processing.
     */
    public function loketRawatInap()
    {
        $today = today();
        $loketName = 'Loket Rawat Inap';

        $totalToday = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_inap')->count();
        $waitingVerification = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_inap')->where('status', 'pending')->count();
        $verified = Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_inap')->where('status', 'verified')->count();

        $currentlyServing = QueueTicket::with('registration')
            ->where('loket_name', $loketName)
            ->whereDate('created_at', $today)
            ->where('status', 'serving')
            ->orderByDesc('served_at')
            ->first();

        $roomOccupancy = [
            ['name' => 'VIP', 'total' => 10, 'used' => Registration::where('service_type', 'rawat_inap')->where('room_class', 'VIP')->whereIn('status', ['verified', 'in_progress'])->count()],
            ['name' => 'Kelas 1', 'total' => 20, 'used' => Registration::where('service_type', 'rawat_inap')->where('room_class', 'Kelas 1')->whereIn('status', ['verified', 'in_progress'])->count()],
            ['name' => 'Kelas 2', 'total' => 30, 'used' => Registration::where('service_type', 'rawat_inap')->where('room_class', 'Kelas 2')->whereIn('status', ['verified', 'in_progress'])->count()],
            ['name' => 'Kelas 3', 'total' => 40, 'used' => Registration::where('service_type', 'rawat_inap')->where('room_class', 'Kelas 3')->whereIn('status', ['verified', 'in_progress'])->count()],
        ];

        $bedAvailable = collect($roomOccupancy)->sum(fn ($r) => $r['total'] - $r['used']);

        $stats = [
            'total_today' => $totalToday,
            'waiting_verification' => $waitingVerification,
            'verified' => $verified,
            'current_queue' => $currentlyServing?->queue_number ?? '-',
            'bed_available' => $bedAvailable,
            'completed_today' => Registration::whereDate('visit_date', $today)->where('service_type', 'rawat_inap')->where('status', 'completed')->count(),
            'waiting_queue' => QueueTicket::where('loket_name', $loketName)->whereDate('created_at', $today)->where('status', 'waiting')->count(),
        ];

        $registrations = Registration::with('queueTicket')
            ->whereDate('visit_date', $today)
            ->where('service_type', 'rawat_inap')
            ->orderByDesc('created_at')
            ->get();

        // Payment distribution
        $paymentDistribution = Registration::whereDate('visit_date', $today)
            ->where('service_type', 'rawat_inap')
            ->select('patient_type', DB::raw('COUNT(*) as count'))
            ->groupBy('patient_type')
            ->pluck('count', 'patient_type')
            ->toArray();

        $bedStats = $this->simrs->getBedStatistics();
        $readyBeds = $this->simrs->getReadyBeds();

        return view('dashboard.loket-rawat-inap', compact(
            'stats',
            'registrations',
            'roomOccupancy',
            'paymentDistribution',
            'currentlyServing',
            'bedStats',
            'readyBeds'
        ));
    }

    /**
     * Dedicated Kamar & Bed Ready View (SIMRS Live Monitoring).
     */
    public function kamarReady(Request $request)
    {
        $classFilter = $request->query('class', 'all');
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search', '');

        $filters = [];
        if ($classFilter && $classFilter !== 'all') {
            $filters['class'] = $classFilter;
        }
        if ($statusFilter && $statusFilter !== 'all') {
            $filters['status'] = $statusFilter;
        }
        if ($search) {
            $filters['search'] = $search;
        }

        $beds = $this->simrs->getRooms($filters);
        $bedStats = $this->simrs->getBedStatistics();

        // Active inpatient registrations needing or assigned a bed
        $waitingPatients = Registration::where('service_type', 'rawat_inap')
            ->whereIn('status', ['pending', 'verified', 'in_progress'])
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.kamar', compact('beds', 'bedStats', 'classFilter', 'statusFilter', 'search', 'waitingPatients'));
    }

    /**
     * API: Get ready beds from SIMRS (JSON).
     */
    public function apiSimrsBeds(Request $request)
    {
        $class = $request->query('class');
        $beds = $this->simrs->getReadyBeds($class);

        return response()->json([
            'success' => true,
            'beds' => $beds,
            'total_ready' => count($beds),
        ]);
    }

    /**
     * API: Assign a ready bed to an inpatient registration.
     */
    public function assignBed(Request $request, int $registrationId)
    {
        if (Auth::user()?->role !== 'loket_rawat_inap') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Petugas Loket Rawat Inap yang memiliki wewenang mengalokasikan kamar SIMRS.',
            ], 403);
        }

        $request->validate([
            'bed_id' => 'required|integer',
            'bed_code' => 'required|string',
            'room_name' => 'required|string',
            'room_class' => 'required|string',
        ]);

        $registration = Registration::findOrFail($registrationId);

        // Assign bed in SIMRS
        $simrsResult = $this->simrs->assignBed($request->input('bed_id'), [
            'patient_name' => $registration->patient_name,
            'registration_number' => $registration->registration_number,
            'doctor_name' => $registration->doctor_name,
        ]);

        if (! ($simrsResult['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => $simrsResult['message'] ?? 'Gagal mengalokasikan kamar di SIMRS.',
            ], 422);
        }

        // Update registration notes and room_class
        $registration->update([
            'room_class' => $request->input('room_class'),
            'notes' => 'Alokasi Bed SIMRS: '.$request->input('bed_code').' ('.$request->input('room_name').')',
            'status' => in_array($registration->status, ['pending', 'verified']) ? 'in_progress' : $registration->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pasien '.$registration->patient_name.' berhasil ditempatkan di Bed '.$request->input('bed_code').' ('.$request->input('room_name').').',
            'registration' => $registration,
        ]);
    }

    /**
     * API: Call next queue ticket (Panggil / Panggil Ulang).
     */
    public function callNext(Request $request)
    {
        $request->validate([
            'loket_name' => 'required|string',
            'ticket_id' => 'nullable|integer',
        ]);

        $loketName = $request->input('loket_name');
        $ticketId = $request->input('ticket_id');

        // If specific ticket ID is requested
        if ($ticketId) {
            $ticket = QueueTicket::with('registration')
                ->where('id', $ticketId)
                ->where('loket_name', $loketName)
                ->firstOrFail();

            // If already serving this exact ticket, it's a re-call
            if ($ticket->status === 'serving') {
                $ticket->update(['called_at' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Memanggil ulang antrean '.$ticket->queue_number,
                    'ticket' => $ticket,
                ]);
            }

            $ticket->update([
                'status' => 'serving',
                'called_at' => now(),
                'served_at' => now(),
                'served_by' => Auth::id() ?? $ticket->served_by,
                'counter_number' => $request->input('counter_number', 1),
            ]);

            $ticket->registration->update(['status' => 'in_progress']);

            return response()->json([
                'success' => true,
                'message' => 'Memanggil antrean '.$ticket->queue_number,
                'ticket' => $ticket,
            ]);
        }

        // Check if current user is already serving an active ticket
        $alreadyServing = QueueTicket::where('loket_name', $loketName)
            ->where('served_by', Auth::id())
            ->where('status', 'serving')
            ->first();

        if ($alreadyServing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih melayani antrean '.$alreadyServing->queue_number.'. Selesaikan atau lewati terlebih dahulu.',
            ], 422);
        }

        // Get next waiting ticket (FIFO)
        $ticket = QueueTicket::with('registration')
            ->where('loket_name', $loketName)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->orderBy('created_at')
            ->lockForUpdate()
            ->first();

        if (! $ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada antrean yang menunggu.',
            ], 404);
        }

        $ticket->update([
            'status' => 'serving',
            'called_at' => now(),
            'served_at' => now(),
            'served_by' => Auth::id(),
            'counter_number' => $request->input('counter_number', 1),
        ]);

        $ticket->registration->update(['status' => 'in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'Memanggil antrean '.$ticket->queue_number,
            'ticket' => $ticket,
        ]);
    }

    /**
     * API: Complete serving a ticket (Selesaikan).
     */
    public function completeTicket(Request $request, int $ticketId)
    {
        $ticket = QueueTicket::where('id', $ticketId)
            ->whereIn('status', ['called', 'serving'])
            ->firstOrFail();

        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $ticket->registration->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Antrean '.$ticket->queue_number.' telah selesai dilayani.',
        ]);
    }

    /**
     * API: Skip a ticket (Lewati).
     */
    public function skipTicket(Request $request, int $ticketId)
    {
        $ticket = QueueTicket::where('id', $ticketId)
            ->whereIn('status', ['waiting', 'called', 'serving'])
            ->firstOrFail();

        $ticket->update(['status' => 'skipped']);
        $ticket->registration->update(['status' => 'skipped']);

        return response()->json([
            'success' => true,
            'message' => 'Antrean '.$ticket->queue_number.' dilewati.',
        ]);
    }

    /**
     * API: Verify a registration (Verifikasi).
     */
    public function verifyRegistration(Request $request, int $registrationId)
    {
        $registration = Registration::where('id', $registrationId)
            ->where('status', 'pending')
            ->firstOrFail();

        $registration->update(['status' => 'verified']);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran '.$registration->registration_number.' terverifikasi.',
        ]);
    }

    /**
     * Get loket statuses for admin monitoring.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getLoketStatuses(): array
    {
        $lokets = ['Loket Rawat Jalan', 'Loket Rawat Inap'];
        $statuses = [];

        foreach ($lokets as $loket) {
            $servingTicket = QueueTicket::where('loket_name', $loket)
                ->whereDate('created_at', today())
                ->where('status', 'serving')
                ->with('registration')
                ->first();

            $waitingCount = QueueTicket::where('loket_name', $loket)
                ->whereDate('created_at', today())
                ->where('status', 'waiting')
                ->count();

            $completedCount = QueueTicket::where('loket_name', $loket)
                ->whereDate('created_at', today())
                ->where('status', 'completed')
                ->count();

            $totalCount = QueueTicket::where('loket_name', $loket)
                ->whereDate('created_at', today())
                ->count();

            $statuses[] = [
                'name' => $loket,
                'status' => $servingTicket ? 'active' : ($waitingCount > 0 ? 'idle' : 'offline'),
                'current_number' => $servingTicket?->queue_number ?? '-',
                'current_patient' => $servingTicket?->registration?->patient_name ?? '-',
                'waiting' => $waitingCount,
                'completed' => $completedCount,
                'total' => $totalCount,
                'progress' => $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0,
            ];
        }

        return $statuses;
    }

    /**
     * Get kanban board data grouped by workflow stage.
     *
     * @return array<string, Collection<int, Registration>>
     */
    private function getKanbanData(): array
    {
        $today = today();

        return [
            'waiting' => Registration::with('queueTicket')
                ->whereDate('visit_date', $today)
                ->whereIn('status', ['pending', 'verified'])
                ->whereHas('queueTicket', fn ($q) => $q->where('status', 'waiting'))
                ->orderBy('created_at')
                ->limit(8)
                ->get(),
            'in_progress' => Registration::with('queueTicket')
                ->whereDate('visit_date', $today)
                ->where('status', 'in_progress')
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get(),
            'on_hold' => Registration::with('queueTicket')
                ->whereDate('visit_date', $today)
                ->whereIn('status', ['skipped'])
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get(),
            'completed' => Registration::with('queueTicket')
                ->whereDate('visit_date', $today)
                ->where('status', 'completed')
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get(),
        ];
    }

    /**
     * Patient Portal Dashboard — Exclusive for registered patients.
     */
    public function pasien()
    {
        $user = Auth::user();
        $today = today();

        // Retrieve registrations associated with this patient
        $registrations = Registration::with('queueTicket')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('patient_name', $user->name);
            })
            ->orderByDesc('created_at')
            ->get();

        // Find active registration for today
        $activeRegistration = $registrations->first(function ($reg) use ($today) {
            return $reg->visit_date && $reg->visit_date->isSameDay($today)
                && in_array($reg->status, ['pending', 'verified', 'in_progress'])
                && $reg->queueTicket
                && in_array($reg->queueTicket->status, ['waiting', 'called', 'serving']);
        });

        $queueInfo = null;
        if ($activeRegistration && $activeRegistration->queueTicket) {
            $ticket = $activeRegistration->queueTicket;
            $loketName = $ticket->loket_name;

            $currentServingTicket = QueueTicket::where('loket_name', $loketName)
                ->whereDate('created_at', $today)
                ->where('status', 'serving')
                ->orderByDesc('served_at')
                ->first();

            $waitingAhead = QueueTicket::where('loket_name', $loketName)
                ->whereDate('created_at', $today)
                ->where('status', 'waiting')
                ->where('id', '<', $ticket->id)
                ->count();

            $queueInfo = [
                'ticket' => $ticket,
                'current_serving' => $currentServingTicket?->queue_number ?? '-',
                'waiting_ahead' => $waitingAhead,
                'estimated_wait_minutes' => max(5, $waitingAhead * 10),
            ];
        }

        $stats = [
            'total_registrations' => $registrations->count(),
            'active_ticket_exists' => (bool) $activeRegistration,
            'completed_visits' => $registrations->where('status', 'completed')->count(),
            'cancelled_visits' => $registrations->where('status', 'cancelled')->count(),
        ];

        return view('dashboard.pasien', compact('registrations', 'activeRegistration', 'queueInfo', 'stats'));
    }

    /**
     * Cancel a registration (by patient or admin).
     */
    public function cancelRegistration(Request $request, int $registrationId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $registration = Registration::with('queueTicket')->findOrFail($registrationId);

        // Security check: only admin or the owning patient can cancel
        if ($user->role === 'pasien' && $registration->user_id !== $user->id && $registration->patient_name !== $user->name) {
            abort(403, 'Anda tidak berhak membatalkan pendaftaran ini.');
        }

        if (! in_array($registration->status, ['pending', 'verified'])) {
            return back()->withErrors(['cancel' => 'Pendaftaran yang sedang diproses atau sudah selesai tidak dapat dibatalkan.']);
        }

        $reason = $request->input('reason', 'Dibatalkan oleh pasien');

        $registration->update([
            'status' => 'cancelled',
            'notes' => trim(($registration->notes ? $registration->notes.' | ' : '').'Dibatalkan: '.$reason),
        ]);

        if ($registration->queueTicket && in_array($registration->queueTicket->status, ['waiting', 'called'])) {
            $registration->queueTicket->update(['status' => 'skipped']);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran '.$registration->registration_number.' berhasil dibatalkan.',
            ]);
        }

        return back()->with('success', 'Pendaftaran '.$registration->registration_number.' berhasil dibatalkan.');
    }
}
