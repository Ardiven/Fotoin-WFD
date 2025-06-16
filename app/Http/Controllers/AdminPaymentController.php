<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'package', 'proofs'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'package.features', 'installments', 'proofs']);
        
        return view('admin.payments.show', compact('payment'));
    }

    public function approveProof(Payment $payment)
    {
        DB::beginTransaction();
        
        try {
            $proof = $payment->proofs()
            ->where('status', 'pending')
            ->first();
            $proof->update([
                'status' => 'approved',
                'admin_notes' => "Bukti pembayaran telah disetujui.",
            ]);

            // Update installment status
            $installment = $payment->installments()
                ->where('status', 'pending')
                ->orderBy('installment_number')
                ->first();

            if ($installment) {
                $installment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            // Check if all installments are paid
            $payment = $proof->payment;
            $allPaid = $payment->installments()
                ->where('status', '!=', 'paid')
                ->count() === 0;

            if ($allPaid) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
                $payment->booking->update(['status' => 'completed']);
                $payment->booking->update(['payment_status' => 'paid']);
            } else {
                $payment->update(['status' => 'processing']);
            }

            DB::commit();

            return redirect()->route('photographer.payment.show', $payment)->with('success', 'Bukti pembayaran berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('photographer.payment.show', $payment)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectProof(Payment $payment)
    {
        $proof = $payment->proofs()
            ->where('status', 'pending')
            ->first();
        $proof->update([
            'status' => 'rejected',
            'admin_notes' => "Bukti pembayaran telah ditolak",
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil ditolak.');
    }
    public function custHist(){
        return view('payment.cust_history');
    }


}
