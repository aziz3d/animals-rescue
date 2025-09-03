<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $donations = [
            [
                'donor_name' => 'Jennifer Smith',
                'donor_email' => 'jennifer.smith@email.com',
                'amount' => 100.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_1234567890',
                'created_at' => now()->subDays(2),
            ],
            [
                'donor_name' => 'Mark Johnson',
                'donor_email' => 'mark.johnson@email.com',
                'amount' => 25.00,
                'type' => 'recurring',
                'status' => 'completed',
                'payment_method' => 'PayPal',
                'transaction_id' => 'pp_9876543210',
                'created_at' => now()->subDays(5),
            ],
            [
                'donor_name' => 'Lisa Brown',
                'donor_email' => 'lisa.brown@email.com',
                'amount' => 250.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_2345678901',
                'created_at' => now()->subDays(7),
            ],
            [
                'donor_name' => 'Anonymous',
                'donor_email' => 'anonymous@donor.com',
                'amount' => 500.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => 'bt_3456789012',
                'created_at' => now()->subDays(10),
            ],
            [
                'donor_name' => 'Patricia Wilson',
                'donor_email' => 'patricia.wilson@email.com',
                'amount' => 50.00,
                'type' => 'recurring',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_4567890123',
                'created_at' => now()->subDays(12),
            ],
            [
                'donor_name' => 'James Davis',
                'donor_email' => 'james.davis@email.com',
                'amount' => 75.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'PayPal',
                'transaction_id' => 'pp_5678901234',
                'created_at' => now()->subDays(15),
            ],
            [
                'donor_name' => 'Maria Garcia',
                'donor_email' => 'maria.garcia@email.com',
                'amount' => 30.00,
                'type' => 'recurring',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_6789012345',
                'created_at' => now()->subDays(18),
            ],
            [
                'donor_name' => 'Robert Miller',
                'donor_email' => 'robert.miller@email.com',
                'amount' => 150.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_7890123456',
                'created_at' => now()->subDays(20),
            ],
            [
                'donor_name' => 'Susan Anderson',
                'donor_email' => 'susan.anderson@email.com',
                'amount' => 40.00,
                'type' => 'recurring',
                'status' => 'completed',
                'payment_method' => 'PayPal',
                'transaction_id' => 'pp_8901234567',
                'created_at' => now()->subDays(22),
            ],
            [
                'donor_name' => 'Thomas Taylor',
                'donor_email' => 'thomas.taylor@email.com',
                'amount' => 200.00,
                'type' => 'one-time',
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'transaction_id' => 'txn_9012345678',
                'created_at' => now()->subDays(25),
            ],
            [
                'donor_name' => 'Karen White',
                'donor_email' => 'karen.white@email.com',
                'amount' => 60.00,
                'type' => 'one-time',
                'status' => 'pending',
                'payment_method' => 'Bank Transfer',
                'transaction_id' => null,
                'created_at' => now()->subDays(1),
            ],
            [
                'donor_name' => 'Daniel Martinez',
                'donor_email' => 'daniel.martinez@email.com',
                'amount' => 35.00,
                'type' => 'recurring',
                'status' => 'failed',
                'payment_method' => 'Credit Card',
                'transaction_id' => null,
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($donations as $donationData) {
            Donation::create($donationData);
        }
    }
}