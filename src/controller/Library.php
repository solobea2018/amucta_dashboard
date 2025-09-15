<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class Library
{
    public function index()
    {
        // Build main content
        $content = <<<HTML
<div class="library-page">
    <h2>AMUCTA Library</h2>
    <p>AMUCTA library is central facility to academic life, offering a wide range of services that go beyond book lending. AMUCTA library supports learning, teaching, and research across disciplines.</p>
    <p>AMUCTA library provides a number of services including: Subscriptions to academic databases like JSTOR, Taylor and Francis, Oxford, MSP, Elgaonline, EBSCO. The library also offers ICT tools and study spaces. AMUCTA has also access to resources such as E-Books, E-Journals. The library also has special collections of research dissertations, reading pamphlets, and hard copy journals.</p>
    <p>We thus encourage and invite all academicians, researchers, and students to make AMUCTA library their home for their academic excellence. Embracing AMUCTA motto - <em>Seeking Wisdom in Truth</em>, quality customer service is our overarching goal. We welcome you to enjoy our friendly, expert, and knowledgeable staff who are ready all the time to welcome and assist you.</p>

    <h2>Library Rules and Regulations</h2>
    <ol>
        <li>All registered staff and students of AMUCTA are given access to use the library services. Users must have current AMUCTA identity card which must be produced when entering the library and borrowing library materials at any time.</li>
        <li>The person registered and given library borrowing cards is responsible for all materials borrowed on those cards. Lost or stolen cards should be reported immediately to the library office which issued them.</li>
    </ol>

    <h2>Library Hours</h2>
    <div class="library-hours">
        <p>Monday – Friday: 09:00 HRS – 22:00 HRS</p>
        <p>Saturday: 09:00 HRS – 18:00 HRS</p>
        <p>Sunday & Public Holidays: <span class="no-services">No Services</span></p>
    </div>
</div>
HTML;

        // Styles
        $head = <<<HTML
<style>
.library-page {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 12px;
    font-family: Arial, sans-serif;
    color: #333;
}
.library-page h2 {
    color: var(--amucta-blue);
    font-size: 1.8rem;
    margin-bottom: 15px;
}
.library-page p {
    text-align: justify;
    margin: 10px 0;
}
.library-page ol {
    margin-left: 20px;
    margin-bottom: 20px;
}
.library-page ol li {
    margin-bottom: 10px;
}
.library-hours {
    background: #e0f2fe;
    padding: 15px;
    border-radius: 8px;
    font-weight: bold;
    color: #1e3a8a;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.library-hours .no-services {
    color: red;
    font-weight: bold;
}
</style>
HTML;

        $title = "AMUCTA Library";

        // Render the page
        MainLayout::render($content, $head, $title);
    }
    public function ict()
    {
        // Build main content
        $content = <<<HTML
<div class="ict-page">
    <div class="ict-content">
        <div class="ict-text">
            <h1>Information Technology Services</h1>
            <p>The university has a good number of fast computers on a very high-speed internet connection (Fiber Network) to allow all students and staff to easily obtain information from the internet for normal learning and extensive academic research. The Information Technology department provides the infrastructure and the support for all technology initiatives of the University, placing priority on integrity, availability, reliability, and security of these campus resources.</p>
        </div>
        <div class="ict-image">
            <img src="/assets/images/ict.JPG" alt="ICT Image">
        </div>
    </div>
</div>
HTML;

        // Styles
        $head = <<<HTML
<style>
.ict-page {
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    font-family: Arial, sans-serif;
}
.ict-content {
    display: flex;
    flex-direction: column;
    padding: 20px;
    gap: 20px;
}
.ict-text h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #1D4ED8;
    margin-bottom: 15px;
}
.ict-text p {
    text-align: justify;
    color: #4B5563;
    line-height: 1.6;
}
.ict-image img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
    border-radius: 8px;
}

/* Responsive layout for larger screens */
@media(min-width: 992px) {
    .ict-content {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        height: 32rem;
    }
    .ict-text {
        width: 50%;
        padding-right: 20px;
    }
    .ict-image {
        width: 50%;
        height: 100%;
    }
    .ict-image img {
        height: 100%;
    }
}
</style>
HTML;

        $title = "Information Technology Services - AMUCTA";

        // Render the page
        MainLayout::render($content, $head, $title);
    }


}