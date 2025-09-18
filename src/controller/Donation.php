<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Donation
{
    public function donate1()
    {
        $content = <<<HTML
<div class="flex flex-col max-w-xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Support AMUCTA - Make a Donation</h2>
    <form onsubmit="sendFormSweet(this,event)" action="/donation/save" method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label>Amount (TZS)</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <select name="method" class="form-control" required>
                <option value="">--Select Method--</option>
                <option value="bank">Bank Account</option>
                <option value="mobile">Mobile Money</option>
            </select>
        </div>
        <div class="form-group">
            <label>Transaction ID (if applicable)</label>
            <input type="text" name="transaction_id" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Donate 💖</button>
    </form>
    <a href="/donation/list" class="btn btn-primary">View potential donors</a>
</div>
HTML;

        MainLayout::render($content, null, "Donate to AMUCTA");
    }
    public function donate()
    {
        $content = <<<HTML
<div class="flex flex-col max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg space-y-6">
    <h2 class="text-3xl font-bold text-center mb-4">Support AMUCTA - Make a Donation</h2>

    <!-- Images Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="rounded overflow-hidden shadow-lg">
            <img src="https://data.tetea.store/images/gallery/gallery_68c6dfbec41c8.webp" alt="Students with special needs studying" class="w-full h-48 object-cover">
            <div class="p-2 text-center font-semibold text-green-700">Supporting Students with Special Needs</div>
        </div>
        <div class="rounded overflow-hidden shadow-lg">
            <img src="https://data.tetea.store/images/gallery/gallery_68c6ded4bf322.webp" alt="Business support program" class="w-full h-48 object-cover">
            <div class="p-2 text-center font-semibold text-green-700">Supporting Local Business and Entrepreneurship</div>
        </div>
    </div>
    <p>Archbishop Mihayo University College of Tabora. Name of Account AMUCTA, A/C No. 0150382588700 CRDB BANK (LTD), TABORA BRANCH</p>

    <!-- Donation Form -->
    <form onsubmit="sendFormSweet(this,event)" action="/donation/save" method="POST" class="space-y-4">   
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label>Amount (TZS)</label>
            <input type="number" name="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Payment Method</label>
            <select name="method" class="form-control" required>
                <option value="">--Select Method--</option>
                <option value="bank">Bank Account</option>
                <option value="mobile">Mobile Money</option>
                <option value="card">Credit Card</option>
            </select>
        </div>
        <div class="form-group">
            <label>Transaction ID (if applicable)</label>
            <input type="text" name="transaction_id" class="form-control">
        </div>
        <div class="flex justify-center space-x-4">
            <button type="submit" class="btn btn-primary">Donate 💖</button>
            <a href="/donation/list" class="btn btn-secondary">View Donors</a>
        </div>
    </form>
</div>
HTML;

        MainLayout::render($content, null, "Donate to AMUCTA");
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donation = new \Solobea\Dashboard\Model\Donation();
            $donation->setFullName(htmlspecialchars(trim($_POST['full_name'])));
            $donation->setEmail(filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL));
            $donation->setPhone(htmlspecialchars(trim($_POST['phone'])));
            $donation->setAmount(floatval($_POST['amount']));
            $donation->setMethod($_POST['method']);
            $donation->setTransactionId(htmlspecialchars(trim($_POST['transaction_id'])));

            $db = new Database();
            if ($db->save_donation($donation)) {
                $content = "<p class='text-green-600 font-semibold'>Thank you {$donation->getFullName()} for your generous donation of TZS {$donation->getAmount()}!</p>";
            } else {
                $content = "<p class='text-red-600 font-semibold'>Failed to save donation. Please try again.</p>";
            }
        } else {
            header("Location: /donation/donate");
            exit;
        }
    }
    public function list()
    {
        $db = new Database();
        $donors = $db->select("SELECT * FROM donations ORDER BY create_date DESC");
        $cards = "";

        if (sizeof($donors) > 0) {
            foreach ($donors as $d) {
                $cards .= "<div class='alumni-card'>
                <h3 class='font-bold text-lg'>{$d['full_name']}</h3>
                <p>Email: {$d['email']}</p>
                <p>Phone: {$d['phone']}</p>
                <p>Amount: TZS {$d['amount']}</p>
                <p>Method: {$d['method']}</p>
                <p class='text-green-600 font-semibold'>Thank you for your support!</p>
            </div>";
            }
        } else {
            $cards = "<p>No donations recorded yet.</p>";
        }

        $content = "<div class='alumni-grid'>$cards</div>";
        MainLayout::render($content, null, "AMUCTA Donors");
    }

}