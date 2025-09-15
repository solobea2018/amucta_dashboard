<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\view\MainLayout;

class StudentLife
{
    public function index()
    {
        
    }
    public function student()
    {
        $content = <<<HTML
<section class="hero-section">
    <div class="hero-bg">
        <div class="hero-overlay">
            <div class="hero-text">
                <h1>Students</h1>
            </div>
        </div>
    </div>
</section>

<section class="students-org">
    <div class="container text-center">
        <h2>Students <span class="highlight">Organisation.</span></h2>
        <p>
            The students organisation body - AMUCTASO (AMUCTA Students Organisation) is an umbrella that organises the
            students leadership, students welfare and all students related matters. Every AMUCTA student is a member of
            AMUCTASO while its leadership represents the students to the university administration.
        </p>
    </div>
</section>

<section class="campus-life">
    <div class="container flex-row">
        <div class="campus-info">
            <h1>Campus life:</h1>
            <p>
                Students’ affairs are chiefly managed by the Dean of Students who oversee the students’ services and on-campus activities which includes:
            </p>
            <ul>
                <li>Sports and games</li>
                <li>Accommodation</li>
                <li>Cafeteria services</li>
                <li>Security services</li>
                <li>Transport and mobility</li>
                <li>Socialization and recreation</li>
                <li>Students’ organization (AMUCTASO)</li>
            </ul>
        </div>
        <div class="campus-image">
            <img src="/assets/images/drone.jpg" alt="Campus Life">
        </div>
    </div>
</section>

<section class="sports-games">
    <div class="container flex-row">
        <div class="sports-image">
            <img src="/assets/images/sports.jpeg" alt="Sports and Games">
        </div>
        <div class="sports-info">
            <h1>Sports <span class="highlight">And</span> Games</h1>
            <p>
                AMUCTA is well equipped with sports and games such as football, netball, basketball, and volleyball pitches. Students participate fully according to their preferences.
            </p>
        </div>
    </div>
</section>

<section class="socialization">
    <div class="container flex-row">
        <div class="social-image">
            <img src="/assets/images/recreation.jpeg" alt="Socialization and Recreation">
        </div>
        <div class="social-info">
            <h2>Socialization and Recreation</h2>
            <p>
                AMUCTA facilitates various social and entertainment activities for students including drama, music, social bonanza, inter-year sports competitions, etc.
            </p>
            <h3>Permission</h3>
            <p>
                The Dean of Students in collaboration with Heads of Departments and Deans of Faculty assist students in processing permission to be away from the University College for academic and non-academic issues.
            </p>
            <h3>Students’ organization (AMUCTASO)</h3>
            <p>
                AMUCTASO promotes and protects students’ welfare and needs during their stay at AMUCTA.
            </p>
        </div>
    </div>
</section>
HTML;

        $head = <<<HTML
<style>
/* Hero Section */
.hero-section { position: relative; width: 100%; height: 30rem; }
.hero-bg { background-image: url('/assets/images/about.jpeg'); background-size: cover; background-position: center; width: 100%; height: 100%; }
.hero-overlay { background-color: rgba(0,0,0,0.6); width: 100%; height: 100%; display: flex; align-items: center; }
.hero-text h1 { color: white; font-size: 4rem; margin-left: 2rem; }

/* Container */
.container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
.text-center { text-align: center; }

/* Highlights */
.highlight { color: #2563eb; }

/* Campus Life */
.campus-life .flex-row { display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; }
.campus-info { flex: 1; }
.campus-info h1 { font-size: 2rem; margin-bottom: 1rem; }
.campus-info ul { list-style: disc; padding-left: 1.5rem; margin-top: 1rem; }
.campus-image img { max-width: 100%; border-radius: 1rem; }

/* Sports and Games */
.sports-games .flex-row { display: flex; flex-wrap: wrap; gap: 2rem; align-items: center; margin-top: 2rem; }
.sports-image img { max-width: 100%; border-radius: 1rem; }
.sports-info h1 { font-size: 2rem; margin-bottom: 1rem; }

/* Socialization */
.socialization .flex-row { display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start; margin-top: 2rem; }
.social-image img { max-width: 100%; border-radius: 50%; }
.social-info h2 { font-size: 1.8rem; margin-bottom: 1rem; }
.social-info h3 { font-size: 1.2rem; margin-top: 1rem; color: #111827; }
.social-info p { margin-top: 0.5rem; line-height: 1.6; color: #555; }
</style>
HTML;

        $title = "AMUCTA Students";

        MainLayout::render($content, $head, $title);
    }
    public function by_laws()
    {
        $content = <<<HTML
<section class="hero-section">
    <div class="hero-bg">
        <div class="hero-overlay">
            <div class="hero-text">
                <h1>Students By-Laws</h1>
            </div>
        </div>
    </div>
</section>

<section class="bylaws-content">
    <div class="container">
        <h2>AMUCTA Students By-Laws</h2>
        <p>
            The AMUCTA Students By-Laws are designed to guide the conduct, rights, and responsibilities of students within the University College. Every student is expected to understand and abide by these regulations to ensure a safe, fair, and productive learning environment.
        </p>

        <h3>1. General Conduct</h3>
        <p>
            Students must uphold discipline, respect staff, fellow students, and maintain integrity in all academic and non-academic activities.
        </p>

        <h3>2. Academic Responsibilities</h3>
        <p>
            Students are expected to attend all lectures, tutorials, and practical sessions. They must submit assignments and examinations on time and avoid any form of academic misconduct.
        </p>

        <h3>3. Campus Behaviour</h3>
        <p>
            Respect for university property and fellow students is mandatory. Any form of vandalism, harassment, or misconduct will attract disciplinary measures.
        </p>

        <h3>4. Dress Code</h3>
        <p>
            Students should maintain appropriate attire as per university guidelines while attending lectures, official events, or campus activities.
        </p>

        <h3>5. Hostel Regulations</h3>
        <p>
            Students residing in hostels must follow the accommodation rules, including curfew hours, visitor policies, and cleanliness standards.
        </p>

        <h3>6. Participation in University Activities</h3>
        <p>
            Students are encouraged to participate in academic, social, and sports activities in a responsible manner while respecting the rights of others.
        </p>

        <h3>7. Disciplinary Measures</h3>
        <p>
            Violations of the by-laws may result in warnings, fines, suspension, or expulsion depending on the severity of the misconduct.
        </p>
    </div>
</section>
HTML;

        $head = <<<HTML
<style>
.hero-section { position: relative; width: 100%; height: 20rem; }
.hero-bg { background-color: #2563eb; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.hero-overlay { background-color: rgba(0,0,0,0.4); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.hero-text h1 { color: white; font-size: 3rem; }

.container { max-width: 1000px; margin: 0 auto; padding: 2rem; color: #111827; }
.bylaws-content h2 { font-size: 2.5rem; margin-bottom: 1rem; color: #2563eb; text-align: center; }
.bylaws-content h3 { font-size: 1.5rem; margin-top: 1.5rem; color: #111827; }
.bylaws-content p { font-size: 1rem; line-height: 1.8; margin-top: 0.5rem; text-align: justify; }
</style>
HTML;

        $title = "AMUCTA Students By-Laws";

        MainLayout::render($content, $head, $title);
    }


    public function accommodation()
    {
        $content = <<<HTML
<section class="accommodation">
    <div class="container text-center">
        <h2>Accommodations</h2>
        <p>
            AMUCTA strives to provide students with the best accommodation services. Priority is given based on availability for students who wish to reside in University College hostels. Off-campus students are also assisted appropriately.
        </p>
        <p>Priority categories include:</p>

        <div class="accommodation-cards">
            <div class="card">
                <img src="/assets/images/wheelchair.png" alt="Students with disabilities">
                <p>Students with disabilities</p>
            </div>
            <div class="card">
                <img src="/assets/images/hairstyle.png" alt="Female students">
                <p>Female students</p>
            </div>
            <div class="card">
                <img src="/assets/images/anniversary.png" alt="First year students">
                <p>First year students</p>
            </div>
        </div>
    </div>
</section>

<section class="services">
    <div class="container service-grid">
        <div class="service-card">
            <img src="/assets/images/cafeteria.png" alt="Cafeteria">
            <h3>CAFETERIA SERVICES</h3>
            <p>
                AMUCTA has two cafeterias offering high-quality catering. Students also access financial services like NMB, CRDB, NBC, and Exim Bank.
            </p>
        </div>
        <div class="service-card">
            <img src="/assets/images/encrypted.png" alt="Security">
            <h3>SECURITY SERVICES</h3>
            <p>
                AMUCTA ensures student safety through credible outsourced security services.
            </p>
        </div>
        <div class="service-card">
            <img src="/assets/images/self-driving.png" alt="Transport">
            <h3>TRANSPORT AND MOBILITY</h3>
            <p>
                AMUCTA facilitates transport for academic and social activities including sports, tours, and outreach.
            </p>
        </div>
    </div>
</section>
HTML;

        $head = <<<HTML
<style>
.container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
.text-center { text-align: center; }
h2 { font-size: 2rem; margin-bottom: 1rem; color: #111827; }

/* Accommodation Cards */
.accommodation-cards { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 1rem; }
.card { background: #fff; border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 1rem; width: 200px; text-align: center; }
.card img { width: 60px; height: 60px; margin-bottom: 0.5rem; }

/* Services */
.service-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; margin-top: 2rem; }
.service-card { background: #1f2937; color: #fff; padding: 1.5rem; border-radius: 1rem; width: 300px; text-align: center; }
.service-card img { width: 50px; height: 50px; margin-bottom: 1rem; }
.service-card h3 { font-size: 1.5rem; margin-bottom: 0.5rem; }
.service-card p { color: #d1d5db; line-height: 1.5; }
</style>
HTML;

        $title = "AMUCTA Accommodation";

        MainLayout::render($content, $head, $title);
    }


    public function dispensary()
    {
        $content = <<<HTML
<section class="dispensary-section">
    <div class="container">
        <p class="university-name">AMUCTA</p>

        <h1 class="page-title">DISPENSARY</h1>

        <div class="dispensary-main">
            <div class="dispensary-image">
                <img src="/assets/images/hospital.jpg" alt="Dispensary Image">
            </div>

            <div class="dispensary-info">
                <p>
                    AMUCTA DISPENSARY is the medical unit under Archbishop Mihayo University College
                    of Tabora which provides health services to students, staff members, staff families, and
                    the general public. The unit is located within AMUCTA main campus. The unit operates on a
                    twenty-four hours basis.
                </p>

                <p>
                    Currently our unit provides the following services: Outpatient services (treatment of
                    various diseases conditions), a wide range of laboratory services, counseling and testing for HIV
                    and providing antiretroviral drugs to people living with HIV/AIDS, Reproductive and child health
                    services, Health Education and counseling of students on reproductive health.
                </p>

                <p>
                    Our unit is accredited to offer services to the National Health Insurance Fund (NHIF)
                    beneficiaries.
                </p>
            </div>
        </div>
    </div>
</section>
HTML;

        $head = <<<HTML
<style>
.dispensary-section {
    background: #ffffff;
    padding: 40px 20px;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
}
.university-name {
    font-size: 2.5rem;
    font-weight: 600;
    color: #2563eb;
}
.page-title {
    margin-top: 10px;
    font-size: 2.5rem;
    font-weight: bold;
    color: #111827;
}
.dispensary-main {
    position: relative;
    margin-top: 40px;
    display: flex;
    flex-direction: column;
}
.dispensary-main::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 300px;
    background: #2563eb;
    border-radius: 16px;
    z-index: -1;
}
.dispensary-image img {
    width: 100%;
    max-width: 500px;
    border-radius: 16px;
    object-fit: cover;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.dispensary-info p {
    margin-top: 15px;
    line-height: 1.8;
    color: #ffffff;
    text-align: justify;
}
@media(min-width: 768px) {
    .dispensary-main {
        flex-direction: row;
        align-items: flex-start;
        gap: 40px;
    }
    .dispensary-image img {
        max-width: 400px;
        height: auto;
    }
    .dispensary-info {
        flex: 1;
    }
}
</style>
HTML;

        $title = "AMUCTA Dispensary";
        MainLayout::render($content, $head, $title);
    }
    public function others()
    {
        $otherServices = [
            [
                "name" => "Religious Life",
                "desc" => "The University has a Catholic Chaplain. However, students are advised to attend religious services to their respective religions or denominational sites around Tabora municipality. Students are also advised to seek for spiritual advice from their respective religious leaders whose contacts are available at the Chaplaincy office."
            ],
            [
                "name" => "Counseling Services",
                "desc" => "There is one Dean of Students who collaborates with the Chaplain in providing campus ministry services in the University."
            ],
            [
                "name" => "Sports and Games",
                "desc" => "The University has a number of playgrounds for sports and games. Most of our students, however, make use of various public places available for sports in Tabora municipality. We encourage our students to participate actively in games inside and outside the University in order to live a healthy life."
            ],
            [
                "name" => "Health Care",
                "desc" => "The University has its own dispensary for students and staff. The dispensary has adequate and qualified staff. We also use Kitete Regional Hospital and Bugando Medical Centre-Mwanza as referrals in critical cases."
            ],
            [
                "name" => "Cafeteria/Canteen",
                "desc" => "There are three sheds and two kitchens, which are used for canteen purposes. The University has two catering companies who provide food and beverages services to students and staff at affordable prices."
            ],
        ];

        $content = '<section class="other-services"><div class="container">';
        $content .= '<div class="services-grid">';

        foreach ($otherServices as $item) {
            $content .= '<div class="service-card">';
            $content .= '<span class="icon">★</span>'; // placeholder icon
            $content .= '<h2 class="service-name">' . htmlspecialchars($item['name']) . '</h2>';
            $content .= '<p class="service-desc">' . htmlspecialchars($item['desc']) . '</p>';
            $content .= '</div>';
        }

        $content .= '</div>'; // end grid

        // Additional Centre for Inclusive Education content
        $content .= <<<HTML
<div class="inclusive-education">
    <h2>AMUCTA Centre for Inclusive Education</h2>
    <p>
        AMUCTA Centre for Inclusive Education offers various services that include assessment of abilities and disabilities to AMUCTA community and Tanzania community at large.
        In order to offer holistic assessment, AMUCTA has invested in training and employing various experts needed for proper assessment. Currently AMUCTA has professionals who form a multi-disciplinary assessment team as follows:
    </p>
    <ul class="specialists-list">
        <li>Audiologists (For hearing assessment)</li>
        <li>Visual Impairment Specialists</li>
        <li>Psychologist</li>
        <li>Learning Disabilities and Autism Specialist</li>
        <li>Communication and Language Specialists</li>
        <li>An assessor for reading and writing skills</li>
        <li>A specialist in Physical disabilities</li>
    </ul>
    
    <p>With the above experts various disabilities and abilities which can be assessed by the Centre include the following:</p>
<ul class="specialists-list">
<li>Hearing state</li>

<li>Visual state</li>

<li>Memory</li>

<li>Attention</li>

<li>Language development</li>

<li>Language comprehension</li>

<li>Reading and writing skills</li>

<li>Speech</li>

<li>Behaviour challenges and Personality</li>

<li>Mental State Examination.</li>
</ul>
</div>
HTML;

        $content .= '</div></section>';

        $head = <<<HTML
<style>
.other-services {
    background: #ffffff;
    padding: 40px 20px;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
}
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.service-card {
    background: #1f2937;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    color: #ffffff;
}
.service-card .icon {
    display: inline-block;
    background: #2563eb;
    padding: 10px;
    border-radius: 50%;
    margin-bottom: 10px;
    font-size: 1.2rem;
}
.service-card .service-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 10px;
}
.service-card .service-desc {
    font-size: 1rem;
    text-align: justify;
    color: #d1d5db;
}
.inclusive-education {
    margin-top: 50px;
}
.inclusive-education h2 {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    margin-bottom: 10px;
}
.inclusive-education p {
    font-size: 1rem;
    color: #4b5563;
    text-align: justify;
    margin-bottom: 15px;
}
.specialists-list {
    list-style: disc;
    padding-left: 20px;
    color: #111827;
}
.specialists-list li {
    margin-bottom: 5px;
}
</style>
HTML;

        $title = "AMUCTA Other Services";
        MainLayout::render($content, $head, $title);
    }




}