<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use App\Models\PaymentInstallment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasRole('customer')) {
            $payments = Payment::with(['package', 'installments', 'proofs'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);


            $pendingCount = Payment::where('status', 'pending')->Where('user_id', Auth::id())->count();

            $completedCount = Payment::where('status', 'completed')->Where('user_id', Auth::id())->count();

            $processingCount = Payment::where('status', 'processing')->Where('user_id', Auth::id())->count();

            $totalRevenue = Payment::where('status', 'completed')->Where('user_id', Auth::id())->sum('amount');

            return view('customer.payment.index', compact(
                'payments',
                'pendingCount',
                'completedCount',
                'processingCount',
                'totalRevenue'
            ));
        }elseif (Auth::user()->hasRole('photographer')) {
            if(!Auth::user()->photographerProfile){
            return redirect()->route('photographer.profile')->with('error', 'Please complete your photographer profile first.');
        }
            $user = Auth::user();

            $payments = $user->packagePayments()->latest()->paginate(10)->withQueryString();

            $pendingCount = $user->packagePayments()->where('status', 'pending')->count();
            $completedCount = $user->packagePayments()->where('status', 'completed')->count();
            $processingCount = $user->packagePayments()->where('status', 'processing')->count();
            $totalRevenue = $user->packagePayments()->where('status', 'completed')->sum('amount');


            return view('payment.index', compact(
                'payments',
                'pendingCount',
                'completedCount',
                'processingCount',
                'totalRevenue'
            ));
        }


    }

   public function create(Booking $booking)
    {
        $booking->load('package');

        if (!$booking->package) {
            abort(404, 'Paket tidak ditemukan untuk booking ini.');
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', 'Booking belum dikonfirmasi.');
        }

        $photographer = User::find($booking->package->user_id);
        return view('customer.payment.create', compact('booking', 'photographer'));
    }


    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,e_wallet,credit_card,cash',
            'installment_type' => 'required|in:full,dp_50,installment_3',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        
        try {
            $payment = new Payment();
            $payment->user_id = Auth::id();
            $payment->booking_id = $booking->id;
            $payment->payment_code = $payment->generatePaymentCode();
            $payment->amount = $booking->package->price;
            $payment->payment_method = $request->payment_method;
            $payment->notes = $request->notes;
            $payment->expired_at = now()->addDays(3); // 3 hari untuk pembayaran
            $payment->save();

            // Create installments based on type
            $this->createInstallments($payment, $request->installment_type);

            DB::commit();

            return redirect()
                ->route('customer.payment.index')
                ->with('success', 'Pembayaran berhasil dibuat. Silakan lakukan pembayaran sesuai instruksi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        if(Auth::user()->hasRole('customer')) {
            $this->authorizePayment($payment);
            $payment->load(['package.features', 'installments', 'proofs']);
            return view('customer.payment.detail', compact('payment'));
        }elseif(Auth::user()->hasRole('photographer')) {
            return view('payment.detail', compact('payment'));
        }
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        

        $this->authorizePayment($payment);

        if ($payment->status !== 'pending' && $payment->status !== 'processing') {
            return redirect()->route('customer.payment.show', $payment)->with('error', 'Pembayaran tidak dapat diupload bukti pembayaran.');
        }

        $proof = new PaymentProof();
        $proof->payment_id = $payment->id;
        $imagePath = null;
        if ($request->hasFile('payment_proof')) {
            $image = $request->file('payment_proof');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('payment_proofs', $imageName, 'public');
        }

        $proof->file_path = $imagePath;
        $proof->original_name = $request->payment_proof->getClientOriginalName();
        $proof->save();
        $payment->status = 'processing';
        $payment->save();

        // $file = $request->file('proof_file');
        // $filename = time() . '_' . $file->getClientOriginalName();
        // $path = $file->storeAs('payment_proofs', $filename, 'public');

        return redirect()->route('customer.payment.show', $payment)->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    public function cancel(Payment $payment)
    {
        $this->authorizePayment($payment);
        
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran tidak dapat dibatalkan.');
        }

        $payment->update(['status' => 'cancelled']);

        return redirect()
            ->route('customer.payment.index')
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }

    private function createInstallments(Payment $payment, string $type)
    {
        $amount = $payment->amount;
        
        switch ($type) {
            case 'full':
                PaymentInstallment::create([
                    'payment_id' => $payment->id,
                    'installment_number' => 1,
                    'amount' => $amount,
                    'due_date' => now()->addDays(3),
                ]);
                break;
                
            case 'dp_50':
                // DP 50%
                PaymentInstallment::create([
                    'payment_id' => $payment->id,
                    'installment_number' => 1,
                    'amount' => $amount * 0.5,
                    'due_date' => now()->addDays(3),
                ]);
                
                // Pelunasan 50%
                PaymentInstallment::create([
                    'payment_id' => $payment->id,
                    'installment_number' => 2,
                    'amount' => $amount * 0.5,
                    'due_date' => now()->addDays(30),
                ]);
                break;
                
            case 'installment_3':
                for ($i = 1; $i <= 3; $i++) {
                    PaymentInstallment::create([
                        'payment_id' => $payment->id,
                        'installment_number' => $i,
                        'amount' => $amount / 3,
                        'due_date' => now()->addDays($i * 30),
                    ]);
                }
                break;
        }
    }

    private function authorizePayment(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment.');
        }
    }
        public function showPay(Payment $payment)
    {
        $installment = $payment->installments->where('status', 'pending')->first();
        return view('customer.payment.pay', compact('installment', 'payment'));
    }
    public function pay(Payment $payment)
    {
        $paymentMethod = $payment->payment_method;
        $installment = $payment->installments->where('status', 'pending')->first();
        return view('customer.payment.uploadProof', compact('payment', 'installment', 'paymentMethod'));
    }
}