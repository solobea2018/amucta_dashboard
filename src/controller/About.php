<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class About
{
    public function index()
    {

    }

    public function about()
    {
        $backgroundHeading = "Background";
        $bgFirstParr = "Archbishop Mihayo University College of Tabora (AMUCTA), a Constituent College of St Augustine University of Tanzania (SAUT), officially opened on 5th November 2010.   The intention for launching the University College was in line with the goal of the Catholic Bishops of Tanzania to ensure that we have a training that would impart academic and professional skills and infuse values of civic and social learning to our students. AMUCTA, therefore, adheres to SAUT vision of holistic development of a person and respect for human dignity. In recognition of this vision, AMUCTA continues to uphold the SAUT motto of Building the City of God and cherish our own motto Seeking Wisdom in Truth.";
        $bgSecondParr = "In implementing its envisioned ideal, SAUT found it important to establish this University College for the purpose of shedding light and contributing to higher education. This takes cognisance of the national development vision 2025, which mainly focuses on high quality of life, peace, stability and unity, good governance, a well-educated and learning society, a competitive economy capable of producing sustainable growth and shared benefits.  The rationale of the establishment of AMUCTA in Tabora region was to maintain the vision of the Catholic church of Tanzania to bring higher education services close to the people as it has been its tradition globally, regionally and locally. In this view, Tabora was one of the targets and hence AMUCTA was established.   The main emphasis in curriculum orientation for this University College include secular education and training programmes, research relevant to the national research agenda, consultancy and community services. In 2010/11 academic year, the University College started with Bachelor of Arts with Education and enrolled 886 students.";

        $visionMissionHeading = "Our Vision, Mission and Motto";
        $missionHeading = "Our Mission";
        $visionHeading = "Our Vision";
        $mottoHeading = "Our Motto";

        $mission = [
            "Being a centre of excellence by providing a high quality of education, research and public service",
            "Promoting the pursuit and defence of truth with transparency and honesty, and service with competence and dedication",
            "Developing a sense of caring for personal and community property",
            "A holistic development of the person by providing sound knowledge, higher analytical ability and commitment to generous service and respect for humankind.",
            "Conscious of man's orientation towards God and neighbour and fostering an ethical and service-oriented approach in its academic and professional training, AMUCTA fulfils its goal by preparing persons well equipped to contribute to the ideals of social, economic and political development."
        ];

        $vision = "When the Catholic Bishops of Tanzania decided to extend the Church's services to the provision of higher education they envisioned a training that would not only impart academic and professional skills but also that would inculcate values of civic and social learning and ethics, such as acquisition of national identity, cultural norms, political growth and responsible citizenship. Thus the Church's vision is the holistic development of a person and respect for human dignity.";
        $motto = "AMUCTA continues to uphold the SAUT motto of Building the City of God and cherish our own motto Seeking Wisdom in Truth.";

        // Generate mission list
        $missionList = "<ul class='styled-list'>";
        foreach ($mission as $m) {
            $missionList .= "<li>$m</li>";
        }
        $missionList .= "</ul>";

        // Add CSS
        $head = <<<HTML
<script src="/js/donate.js" defer></script>
<style>
.about-section {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.about-section h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #2c3e50;
    text-align: center;
}
.about-section p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #444;
    text-align: justify;
}
.styled-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
}
.styled-list li {
    margin-bottom: .5rem;
    line-height: 1.6;
    color: #333;
    list-style: disc;
}
.sub-section {
    margin-top: 2rem;
    padding: 1rem;
    border-left: 4px solid #2563eb;
    background: #f9fafb;
    border-radius: 6px;
}
.sub-section h3 {
    margin-bottom: .5rem;
    font-size: 1.4rem;
    color: #2563eb;
}
@media (max-width: 768px) {
    .about-section {
        padding: 1rem;
    }
    .about-section h2 {
        font-size: 1.4rem;
    }
}
</style>
HTML;

        $content = <<<HTML
<div class="about-section">
    <h2>$backgroundHeading</h2>
    <p>$bgFirstParr</p>
    <p>$bgSecondParr</p>

    <div class="sub-section">
        <h3>$visionMissionHeading</h3>
        <h4>$missionHeading</h4>
        $missionList
        <h4>$visionHeading</h4>
        <p>$vision</p>
        <h4>$mottoHeading</h4>
        <p><em>$motto</em></p>
    </div>
</div>
HTML;

        MainLayout::render($content, $head, "About AMUCTA");
    }


    public function history()
    {
        $locationParr = "Archbishop Mihayo University College of Tabora (AMUCTA) is located at Tabora-Viziwi Primary School along Lumumba Street in Tabora Municipality, Nzega Road, adjacent to Tabora Teachers College. The university college only few kilomitres from the main bus stand, the railway station, and Tabora Airport.";
        $mapsDirections = "Location on the Map";
        $mapsDirectionsDesc = "Please navigate the map below to find the exact location of AMUCTA in Tabora Municipality.";

        $head = <<<HTML
<script src="/js/donate.js" defer></script>
<style>
.history-section {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.history-section h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #2c3e50;
    text-align: center;
}
.history-section p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #444;
    text-align: justify;
}
.map-box {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}
.map-box h3 {
    color: #2563eb;
    margin-bottom: .5rem;
    font-size: 1.4rem;
}
.map-embed {
    width: 100%;
    height: 300px;
    border: none;
    border-radius: 6px;
}
@media (max-width: 768px) {
    .history-section {
        padding: 1rem;
    }
    .history-section h2 {
        font-size: 1.4rem;
    }
}
</style>
HTML;

        $content = <<<HTML
<div class="history-section">
    <h2>Our History & Location</h2>
    <p>$locationParr</p>
    <div class="map-box">
        <h3>$mapsDirections</h3>
        <p>$mapsDirectionsDesc</p>
        <iframe class="map-embed"
            src="https://www.google.com/maps?q=Archbishop+Mihayo+University+College+of+Tabora&output=embed"
            allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>
HTML;

        MainLayout::render($content, $head, "History of AMUCTA");
    }

    public function leadership()
    {

    }
    public function governance()
    {

    }
    // In your controller file

    public function privacy()
    {$date=date("F d, Y");
        $head = <<<HTML
<style>
.privacy-section {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.privacy-section h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #2c3e50;
    text-align: center;
}
.privacy-section p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #444;
    text-align: justify;
}
.privacy-section ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}
.privacy-section ul li {
    margin-bottom: .5rem;
    line-height: 1.6;
    color: #333;
    list-style: disc;
}
@media (max-width: 768px) {
    .privacy-section {
        padding: 1rem;
    }
    .privacy-section h2 {
        font-size: 1.4rem;
    }
}
</style>
HTML;

        $content = <<<HTML
<div class="privacy-section">
    <h2>Privacy Policy</h2>
    <p>At Archbishop Mihayo University College of Tabora (AMUCTA), we value and respect your privacy. 
    This policy explains how we collect, use, and safeguard your personal information when you use our 
    websites, applications, and related services.</p>

    <h3>Information We Collect</h3>
    <ul>
        <li>Personal details such as name, email, phone number when you register, apply, or contact us.</li>
        <li>Academic records for student services and administration.</li>
        <li>Technical data like IP address, browser type, and usage statistics.</li>
    </ul>

    <h3>How We Use Your Information</h3>
    <p>We use this data to deliver academic services, improve our systems, communicate with stakeholders, 
    and ensure the security of our platforms.</p>

    <h3>Data Protection</h3>
    <p>AMUCTA applies appropriate security measures to protect your information from unauthorized access 
    or misuse.</p>

    <h3>Information Sharing</h3>
    <p>We do not sell or rent personal information to third parties. Data may only be shared with authorized 
    entities or as required by law.</p>

    <h3>Your Rights</h3>
    <p>You may request access, correction, or deletion of your personal data by contacting AMUCTA administration.</p>

    <p><em>Last updated: $date</em></p>
</div>
HTML;

        MainLayout::render($content, $head, "Privacy Policy - AMUCTA");
    }


    public function terms()
    {$date=date("F d, Y");
        $head = <<<HTML
<style>
.terms-section {
    max-width: 1000px;
    margin: 0 auto;
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.terms-section h2 {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #2c3e50;
    text-align: center;
}
.terms-section p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #444;
    text-align: justify;
}
.terms-section h3 {
    margin-top: 1.5rem;
    margin-bottom: .5rem;
    font-size: 1.4rem;
    color: #2563eb;
}
@media (max-width: 768px) {
    .terms-section {
        padding: 1rem;
    }
    .terms-section h2 {
        font-size: 1.4rem;
    }
}
</style>
HTML;

        $content = <<<HTML
<div class="terms-section">
    <h2>Terms and Conditions</h2>
    <p>These Terms and Conditions govern your use of Archbishop Mihayo University College of Tabora (AMUCTA) 
    websites, systems, and services. By using our services, you agree to comply with these terms.</p>

    <h3>Acceptable Use</h3>
    <p>You agree to use AMUCTA systems only for lawful and academic purposes. Unauthorized access or misuse is prohibited.</p>

    <h3>Accounts and Responsibilities</h3>
    <p>Students and staff are responsible for safeguarding their login credentials and using AMUCTA resources appropriately.</p>

    <h3>Academic and Administrative Services</h3>
    <p>When using online applications, results, or payments, you agree to provide accurate and authorized information.</p>

    <h3>Limitation of Liability</h3>
    <p>AMUCTA is not liable for damages caused by misuse of systems, service interruptions, or third-party actions.</p>

    <h3>Policy Updates</h3>
    <p>AMUCTA reserves the right to update these terms when necessary. Continued use indicates acceptance of the updated terms.</p>

    <p><em>Last updated: $date </em></p>
</div>
HTML;

        MainLayout::render($content, $head, "Terms & Conditions - AMUCTA");
    }



    public function message()
    {
        $principalName = "Rev. Prof. Asantemungu, JUVENALIS (Principal)";
        $principalPhoto = "/assets/staff/asantemungu.jpg";
        $principalParr = [
            "Welcome to Archbishop Mihayo University College of Tabora (AMUCTA), a Constituent College of St Augustine University of Tanzania (SAUT). AMUCTA is a remarkable higher learning institution where hunger for knowledge, drive for excellence and concern for making a difference in the community and in the world at large are a focus. From the beginning, AMUCTA maintains the intention for its launching which is in line with the goal of the Catholic Bishops of Tanzania to ensure that we have a training that would impart academic and professional skills and infuse values of holistic development of a person and respect for human dignity. In recognition of this vision, AMUCTA continues to uphold the SAUT motto of Building the City of God and cherish our own motto Seeking Wisdom in Truth.",
            "AMUCTA continues to grow with a more articulated vision and plan for the role of teaching, research, learning and consultancy, measurable progress in increasing the diversity of its programmes and greater engagement with the community around. It has recruited a pool of qualified academic and administrative staff from within and outside the country. The University College is committed to enrich our students and staff academically and socially. This includes the provision of learning environment that makes the students appreciate the University as an exciting higher learning institution.",
            "Join AMUCTA family to enjoy different programmes currently offered which are: Bachelor of Arts with Education, Bachelor of Business Administration, Bachelor of Education (Special Needs), Diploma in Early Childhood Development with Special Needs Education.",
            "At the beginning of this new academic year, I am more certain than ever of the educational quality and distinctiveness that AMUCTA envisages to offer. To the academic and administrative staff, I offer my thanks for your cooperation and personal contribution in making AMUCTA the best institution conducive for learning. What a grace it is for me to share this work with you and for all of us to be a part of the AMUCTA family. To current students and prospective students, I wish you good luck in your studies and with all that a new academic year and university life entails. Always remember Seeking Wisdom in Truth (Indagare Sapientiam in Veritate), the motto of our University College.",
            "May God's blessings be with you and with AMUCTA University College."
        ];

        // Generate paragraphs
        $paragraphs = "";
        foreach ($principalParr as $p) {
            $paragraphs .= "<p class='mb-4 text-gray'>$p</p>";
        }

        // Add CSS into $head
        $head = <<<HTML
<script src="/js/donate.js" defer></script>
<style>
.message-card {
    max-width: 900px;
    margin: 0 auto;
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.message-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 1.5rem;
}
.message-header img {
    width: 160px;
    height: 160px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    margin-bottom: 1rem;
}
.message-header h2 {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-align: center;
}
.message-header p {
    color: #2563eb;
    font-weight: 600;
    text-align: center;
}
.message-body p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: #444;
    text-align: justify;
}
@media (min-width: 768px) {
    .message-header {
        flex-direction: row;
        align-items: flex-start;
        text-align: left;
    }
    .message-header img {
        margin-right: 1.5rem;
        margin-bottom: 0;
    }
    .message-header h2,
    .message-header p {
        text-align: left;
    }
}
</style>
HTML;

        // Content with styling
        $content = <<<HTML
<div class="message-card">
    <div class="message-header">
        <img src="$principalPhoto" alt="$principalName">
        <div>
            <h2>$principalName</h2>
            <p>Principal, AMUCTA</p>
        </div>
    </div>
    <div class="message-body">
        $paragraphs
    </div>
</div>
HTML;

        MainLayout::render($content, $head, "Principal's Message");
    }


}