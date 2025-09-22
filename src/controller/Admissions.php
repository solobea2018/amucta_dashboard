<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Admissions
{
    public function index()
    {

    }
    public function admission()
    {
        $content = <<<HTML
<div class="admission-container">

    <p class="admission-title" data-aos="fade-right" data-aos-duration="1000">
        Admission to AMUCTA
    </p>

    <p class="admission-text">
        All applications are done online through the following
        <span>
            <a href="https://oas.amucta.ac.tz"><button class="admission-link-btn">Link</button></a>
        </span>
    </p>

    <p class="admission-subtitle" data-aos="fade-right" data-aos-duration="1000">
        Application Procedures
    </p>

    <p class="admission-text">
        Applicants who choose to apply directly to AMUCTA should take these steps:
    </p>
    <p class="admission-bold-text">- Understand the requirements for admission.</p>
    <p class="admission-text">
        Before you apply for any programme make sure you are eligible for that programme. You can get more
        information on programmes offered by AMUCTA <a href="https://oas.amucta.ac.tz" class="admission-link">Here</a>
        or request information from Admission Officer through this Email: <strong>admission@amucta.ac.tz</strong>.
    </p>
    <p class="admission-bold-text">- Don’t miss application deadlines.</p>

    <div class="admission-btn-container">
        <button class="admission-btn">Apply now</button>
    </div>  

    <section class="payment-section">
        <div class="payment-container">
            <h1 class="payment-title">
                Application <br> Payment <span class="payment-highlight">Procedure</span>
            </h1>

            <p class="payment-text">
                You are required to pay application fee of Tshs 10,000/= by using CONTROL NUMBER provided by the OSIM system after creating an account and log in the system.
            </p>

            <div class="payment-grid">

                <!-- M-PESA -->
                <div class="payment-card">
                    <span class="payment-icon">
                        <img src="/assets/images/mpesa.png" alt="M-PESA">
                    </span>
                    <h2 class="payment-card-title">FOR VODACOM (M-PESA)</h2>
                    <div class="payment-card-body">
                        <ul>
                            <li><strong>Dial:</strong> <code>*150*00#</code></li>
                            <li><strong>Enter 1:</strong> Send money (Tuma pesa)</li>
                            <li><strong>Enter 4:</strong> To bank (Kwenda benki)</li>
                            <li><strong>Enter 1:</strong> CRDB</li>
                            <li><strong>Enter 1:</strong> Enter Reference (Weka namba ya kumbukumbu)</li>
                            <li>Enter your reference number from the system</li>
                            <li>Enter amount / Weka kiasi cha kulipa</li>
                            <li>Enter PIN / Weka namba yako ya siri</li>
                            <li><strong>Enter 1</strong> to Confirm or <strong>2</strong> to Cancel</li>
                        </ul>
                    </div>
                </div>

                <!-- TIGO PESA -->
                <div class="payment-card">
                    <span class="payment-icon">
                        <img src="/assets/images/tigopesa.png" alt="Tigo Pesa">
                    </span>
                    <h2 class="payment-card-title">FOR TIGO (TIGOPESA)</h2>
                    <div class="payment-card-body">
                        <ul>
                            <li><strong>Dial:</strong> <code>*150*01#</code> (Piga *150*01#)</li>
                            <li><strong>Enter 7:</strong> Financial services (Huduma za kifedha)</li>
                            <li><strong>Enter 1:</strong> Tigo Pesa to Bank (Tigo pesa kwenda Benki)</li>
                            <li><strong>Enter 1:</strong> CRDB</li>
                            <li><strong>Enter 1:</strong> Enter reference number (Kuingiza namba ya kumbukumbu)</li>
                            <li>Enter your reference number from the system</li>
                            <li>Enter amount to pay / Weka kiasi cha unachotuma</li>
                            <li>Enter PIN to confirm / Ingiza namba ya siri</li>
                        </ul>
                    </div>
                </div>

                <!-- AIRTEL MONEY -->
                <div class="payment-card">
                    <span class="payment-icon">
                        <img src="/assets/images/airtel.jpeg" alt="Airtel Money">
                    </span>
                    <h2 class="payment-card-title">FOR AIRTEL (AIRTEL MONEY)</h2>
                    <div class="payment-card-body">
                        <ul>
                            <li><strong>Dial:</strong> <code>*150*60#</code> (Piga *150*60#)</li>
                            <li><strong>Enter 1:</strong> Send money (Tuma pesa)</li>
                            <li><strong>Enter 3:</strong> To bank (Kwenda benki)</li>
                            <li><strong>Enter 2:</strong> CRDB Bank</li>
                            <li>Enter your reference number from the system</li>
                            <li>Enter amount to pay / Weka kiasi cha kulipa</li>
                            <li>Enter PIN to send the amount to CRDB BANK COLLECTION account (namba yako ya kumbukumbu ya malipo)</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <p class="admission-contact">
        Kwa maelezo zaidi:<br>
        Tembelea ofisi ya usajili iliyopo chuoni
    </p>

    <p class="admission-contact-text">
        Fill all the required information and submit your application:
    </p>

    <p class="admission-apply-link">
        Click <a href="https://oas.amucta.ac.tz/index.php/login/">Here</a> to apply<br>
        In case you face any challenge call:<br>
        +255 767631829, +255 764539031 and +255 714278815
    </p>

    <p class="admission-officer">
        Admissions Officer<br>
        Archbishop Mihayo University College of Tabora<br>
        P.O. BOX 801<br>
        Tabora<br>
        Tanzania
    </p>

    <p class="admission-note">
        Any application that does not follow the above procedure or late application will not be processed
    </p>

</div>

HTML;

        $head = <<<HTML
<style>
.admission-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.admission-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: var(--amucta-blue);
    margin-top: 20px;
}

.admission-subtitle {
    font-size: 2rem;
    font-weight: bold;
    color: var(--amucta-blue);
    margin-top: 30px;
}

.admission-text {
    font-size: 1.125rem;
    text-align: justify;
    margin-top: 10px;
}

.admission-bold-text {
    font-weight: bold;
    margin-top: 10px;
}

.admission-link-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #2563eb;
    color: #fff;
    border: none; !important;
    border-radius: 6px;
    cursor: pointer;
    animation: bounce 2s infinite;
    transition: background-color 0.3s ease;
}

.admission-link-btn:hover {
    background-color: #1e40af;
}

.admission-link {
    color: var(--amucta-blue);
    font-weight: bold;
    text-decoration: underline;
    animation: pulse 2s infinite;
}

.admission-btn-container {
    margin-top: 20px;
}

.admission-btn {
    padding: 10px 20px;
    background-color: #2563eb;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.payment-section {
    background-color: #fff;
    padding: 40px 20px;
}

.payment-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 600;
}

.payment-highlight {
    color: var(--amucta-blue);
}

.payment-text {
    font-size: 1.125rem;
    font-weight: bold;
    margin-top: 20px;
    text-align: justify;
}

.payment-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-top: 30px;
}

@media(min-width: 768px){
    .payment-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(min-width: 1024px){
    .payment-grid { grid-template-columns: repeat(3, 1fr); }
}

.payment-card {
    background: #f3f4f6;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.payment-icon img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.payment-card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-top: 10px;
    color: #374151;
}

.payment-card-body {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 15px;
    text-align: left;
}
.payment-card-body li{
    list-style-type: square;
    list-style-position: inside;
}

.admission-contact, .admission-contact-text, .admission-apply-link, .admission-officer, .admission-note {
    margin-top: 20px;
    font-size: 1rem;
    text-align: justify;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
HTML;

        $title = "Admission to AMUCTA";

        MainLayout::render($content, $head, $title);
    }

    public function joining()
    {
        $content = <<<HTML
<div class="confirmation-container">

    <h2>Confirmation</h2>
    <p>A candidate is required to confirm his or her acceptance to the Admissions Officer within the period prescribed in the joining instructions. If a candidate fails to do so, the University will offer the admission to candidates on the reserve list without further notice. Students from countries outside Tanzania must conform to all immigration formalities in their countries before they depart to Tanzania. They must also obtain a Residence Permit from the nearest Tanzania Embassy or High Commission. This should be done at the earliest possible date. It is advisable when traveling to Tanzania that one keeps readily available his or her academic documents, admission letter as well as financial support documents, or else they may be required at the point of entry by the immigration authorities.</p>

    <h2>Date of Arrival and Registration</h2>
    <p>All selected students are required to report at Archbishop Mihayo University College of Tabora (AMUCTA) on the stated date from 9.00 a.m. for registration. There is an Orientation programme for the new students on days that are put on the notice board.</p>

    <h2>Payment of Fees</h2>
    <p>- No students shall be allowed to register or attend classes unless the required fees have been paid as per invoice. Invoices are available at AMUCTA website.</p>
    <p>- Bring the original pay-in slip with you. Cheques, cash, faxes, scanned pay-in slips; emails of confirmation of payments or any other form of payments are not acceptable.</p>
    <p>- Fees paid will not be refunded if a student withdraws or leaves the university without permission.</p>
    <p>- However, if a student receives prior permission from the Deputy Principal for Academic Affairs (DPAA) to withdraw or to be away from the University and provided that the application in writing is submitted within two weeks of the academic year or semester, fifty percent of the fees may be refunded.</p>
    <p>All prescribed fees shall be paid directly to Archbishop Mihayo University College of Tabora. Name of Account AMUCTA, A/C No. 0150382588700 CRDB BANK (LTD), TABORA BRANCH.</p>

    <h2>Documents for Registration</h2>
    <p>- All students must bring original Certificates or Original Result Slips of Form Four, Form Six and Diploma/Certificate (for candidates selected under equivalent qualifications) and Original Birth Certificate. Note that photocopies, downloaded internet results, faxes, affidavits and certified results are not acceptable.</p>
    <p>- Students shall be registered under the names appearing in their certificates. No change of names shall be accepted unless all requirements provided under SAUT students By-Laws have been observed.</p>
    <p>- Students must register for the course programme for which they have been admitted. No change of programme shall be accepted.</p>

    <h2>During and after Registration</h2>
    <p>- No student shall be allowed to postpone studies after the academic year has begun except under special circumstances. Permission to postpone studies shall be considered after the student has produced satisfactory evidence of the reasons for postponement to the DPAA. Special circumstances shall include ill health or serious social problems.</p>
    <p>- No students shall be allowed to postpone studies during the two weeks preceding final examination, but may for valid reasons be considered for postponement of examinations.</p>
    <p>- Students shall commit themselves in writing to abide by the University Rules and Regulations as required in the registration form. A copy of the students Rules and Regulations shall be made available to the student through the office of the Dean of Students. Students are encouraged to read and abide by them.</p>
    <p>- Students shall be issued identification cards, which they must carry all times and which shall be produced when demanded by appropriate University Officers. The identity card is not transferable and any fraudulent use may result in loss of student privileges or suspension.</p>
    <p>- Loss of the identity card should be reported to the Office of Dean of Students, where a new one can be obtained after paying an appropriate fee (currently Tshs 10,000/=) as stipulated under AMUCTA.</p>
    <p>- A student registered for a course programme at AMUCTA may not enroll concurrently in any other institution without special permission in writing of the appropriate Faculty Dean of the University or, in cases where professional examinations are held under inter-institutional arrangements or cooperation.</p>
    <p>- No exemption will be given from University courses which a student may claim to have done elsewhere.</p>
    <p>- Any late registration is liable to a penalty of Tshs 100,000/= (One hundred thousand shillings only).</p>

    <h2>Accommodation and Hostel Facilities</h2>
    <p>Please be informed that the University College has limited accommodation facilities. The AMUCTA accommodation Policy is both on-campus and off-campus. Most students stay off-campus. Off-campus students have to take care of their accommodation arrangements. The University College is not directly involved in looking off-campus accommodation but assistance may be obtained from the office of the Dean of Students. Students who opt for on-campus accommodation should not pay for the accommodation until a room has been allocated. Rooms will be allocated after registration on request. Allocation will be on first-come first served basis.</p>

    <h2>Meals, Books & Stationary Allowances</h2>
    <p>- AMUCTA does not offer meals to students but private catering facilities are available on campus, around the campus and around off-campus hostels. Students are free to take their meals wherever they choose at their own expense.</p>
    <p>- A sum of 7,500/= is recommended for meals and accommodation per day (pegged on government rates).</p>
    <p>- Students are required to purchase their own stationary, learning facilities (e.g., calculator, camera etc), textbooks as recommended by their course coordinator/lecturer. A sum of 200,000/= is recommended per annum. (Pegged on government rates).</p>

    <h2>Scholarship and Financial Assistance</h2>
    <p>All students are required to settle their sponsorship before registration. Students under HESLB are reminded to read carefully guidelines and criteria for issuing loans for the academic year 2012/2013 under HESLB website (www.heslb.go.tz).</p>

    <h2>Worship</h2>
    <p>AMUCTA recognizes the individuals right of worship. Students are, therefore, advised to use the facilities available for religious services within and outside the campus without interfering academic activities.</p>

    <h2>Academic Activities</h2>
    <p>a. AMUCTA academic activities shall be carried out from Monday through Saturday without prejudice to regulation 7.4 below.</p>
    <p>b. DPAA, after consultation with the Principal, shall have powers to suspend academic activities at any day of the week as the case may be and prescribed a day which the activities shall resume. DPAA shall make the announcement to that effect public to both students and staff.</p>
    <p>c. Public Holiday which fall on AMUCTA working days shall be observed subject to prior arrangements between the lecturer and students where there shall be any academic activity to conducted.</p>
    <p>d. Where any academic activity has been planned on Public Holiday, it shall be communicated to students and Head of Department in writing two days prior to the said activity.</p>
    <p>e. Examinations shall take place any day of the week (Monday to Saturday) as scheduled in the time table even if it falls on a Public Holiday.</p>

    <h2>Communication</h2>
    <p>Students registered at AMUCTA are required to regularly consult notice boards, website www.amucta.ac.tz for any information that may have a bearing on their academic and social impact for their stay at AMUCTA such as timetable, almanac and examination results.</p>

    <h2>Financial Information</h2>
    <p>Fees and other financial obligations are the sole responsibility of the student and/ or the sponsor or guardian. The cost of each course will be clearly stated in the joining instructions. The fees are payable in full at the beginning of the academic year or in two equal installments at the beginning of each semester. All payments by local institutions or individuals shall be made in Tanzania currency. Foreign based institutions or sponsors, whether they are sponsoring a local or foreign student, shall be made in convertible currencies. Fees shall be paid through the University account as it is explained in the joining instructions. Fees may be revised from time to time without prior notice.</p>

    <h2>Celebret</h2>
    <p>Priests are required to obtain their celebret from their respective Local Ordinary or Superior General.</p>

    <h2>Discipline</h2>
    <p>- Students are required to demonstrate good behavior within and outside the Campus. Students must follow AMUCTA Students By- Laws rules and regulations. Failure to observe and fulfill will attract disciplinary action which may include expulsion from the studies.</p>
    <p>- Students are to observe the dressing code approved by the University Council.</p>

</div>

HTML;

        $head = <<<HTML
<style>
.confirmation-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1rem;
    font-family: Arial, sans-serif;
    line-height: 1.7;
    color: #333;
}

.confirmation-container h2 {
    font-size: 1.8rem;
    margin-top: 2rem;
    margin-bottom: 0.8rem;
    font-weight: bold;
    color: #1f2937; /* Dark gray */
}

.confirmation-container p {
    text-align: justify;
    margin-bottom: 1.2rem;
}

@media screen and (max-width: 768px) {
    .confirmation-container {
        padding: 1rem 0.5rem;
    }

    .confirmation-container h2 {
        font-size: 1.5rem;
    }
}

</style>
HTML;

        $title = "Admission to AMUCTA";

        MainLayout::render($content, $head, $title);
    }

    public function fees1()
    {
        $query = "SELECT id, name, fees, duration 
              FROM program 
              ORDER BY level_id";
        $fees = (new Database())->select($query);

        $content = '<div class="fees-container">';
        $content .= '<h1 class="fees-title">Program Fees & Funding</h1>';

        if (!empty($fees)) {
            $content .= '<table class="fees-table">';
            $content .= '<thead>
                        <tr>
                            <th>Program</th>
                            <th>Fees</th>
                            <th>Duration</th>
                        </tr>
                     </thead><tbody>';
            foreach ($fees as $f) {
                $feeAmount = number_format((float)$f['fees'], 2); // format nicely
                $content .= <<<HTML
<tr data-aos="fade-up" data-aos-duration="1000">
    <td class="fee-name"><a href="/programmes/detail/{$f['id']}">{$f['name']}</a></td>
    <td class="fee-amount">{$feeAmount}</td>
    <td class="fee-duration">{$f['duration']}</td>
</tr>
HTML;
            }
            $content .= '</tbody></table>';

            // add the static info under the table
            $content .= <<<HTML
        <div class="fees-info">
            <p class="fees-text">
                All fee details and payment options for our programs can also be found in the official fee structure document.
            </p>
            <p class="fees-link">
                <a href="https://amucta.ac.tz/assets/files/AMUCTA_JOIN_INSTRUCTION.pdf" target="_blank">
                    <button class="fees-btn">View Full Fee Structure</button>
                </a>
            </p>
            <p class="fees-note">
                For further funding information, scholarships, or financial assistance, please contact the Finance Office via email: 
                <strong>finance@amucta.ac.tz</strong> or call +255 26 2605355.
            </p>
        </div>
HTML;
        } else {
            $content .= '<p class="no-fees">No fee information found.</p>';
        }

        $content .= '</div>'; // close container

        $head = <<<HTML
<style>
.fees-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.fees-title {
    font-size: 2rem;
    color: var(--amucta-blue);
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

.fees-table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.fees-table th, 
.fees-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.fees-table th {
    background-color: var(--amucta-dark);
    color: #fff;
    font-weight: bold;
}

.fees-table tr:hover {
    background-color: #f9fafb;
}

.fee-name {
    font-weight: bold;
    color: var(--amucta-blue);
}

.fees-info {
    margin-top: 30px;
    line-height: 1.6;
}

.fees-text, .fees-note {
    font-size: 1.125rem;
    text-align: justify;
    margin-top: 15px;
}

.fees-link {
    margin-top: 20px;
    text-align: center;
}

.fees-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: var(--amucta-blue);
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    animation: bounce 2s infinite;
    transition: background-color 0.3s ease;
}

.fees-btn:hover {
    background-color: var(--amucta-blue);
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.no-fees {
    text-align: center;
    font-size: 1.125rem;
    color: #374151;
    margin-top: 20px;
}
</style>
HTML;

        $title = "Program Fees & Funding";

        MainLayout::render($content, $head, $title);
    }

    public function fees()
    {
        $query = "SELECT id, name, fees, duration 
              FROM program 
              ORDER BY level_id";
        $fees = (new Database())->select($query);

        $content = '<div class="fees-container">';
        $content .= '<h1 class="fees-title">Program Fees</h1>';

        if (!empty($fees)) {
            $content .= '<table class="fees-table">';
            $content .= '<thead>
                        <tr>
                            <th>Program</th>
                            <th>Fees & Funding</th>
                            <th>Duration</th>
                        </tr>
                     </thead><tbody>';
            foreach ($fees as $f) {
                $feeContent = htmlspecialchars_decode($f['fees']); // decode HTML content
                $content .= <<<HTML
<tr data-aos="fade-up" data-aos-duration="1000">
    <td class="fee-name"><a href="/programmes/detail/{$f['id']}">{$f['name']}</a></td>
    <td class="fee-content">{$feeContent}</td>
    <td class="fee-duration">{$f['duration']}</td>
</tr>
HTML;
            }
            $content .= '</tbody></table>';
        } else {
            $content .= '<p class="no-fees">No fee information found.</p>';
        }

        $content .= '</div>'; // close container

        $head = <<<HTML
<style>
.fees-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.fees-title {
    font-size: 2rem;
    color: var(--amucta-blue);
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

.fees-table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.fees-table th, 
.fees-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: top;
}

.fees-table th {
    background-color: var(--amucta-dark);
    color: #fff;
    font-weight: bold;
}

.fees-table tr:hover {
    background-color: #f9fafb;
}

.fee-name {
    font-weight: bold;
    color: var(--amucta-blue);
}

.fee-content {
    font-size: 0.95rem;
    line-height: 1.5;
}

.no-fees {
    text-align: center;
    font-size: 1.125rem;
    color: #374151;
    margin-top: 20px;
}
</style>
HTML;

        $title = "Program Fees";

        MainLayout::render($content, $head, $title);
    }


    public function requirements()
    {
        $query = "SELECT id, name, entry_requirements, duration 
              FROM program 
              ORDER BY level_id";
        $requirements = (new Database())->select($query);

        $content = '<div class="requirements-container">';
        $content .= '<h1 class="requirements-title">Program Entry Requirements</h1>';

        if (!empty($requirements)) {
            $content .= '<table class="requirements-table">';
            $content .= '<thead>
                        <tr>
                            <th>Program</th>
                            <th>Entry Requirements</th>
                            <th>Duration</th>
                        </tr>
                     </thead><tbody>';
            foreach ($requirements as $r) {

                $rqs =htmlspecialchars_decode($r['entry_requirements']);
                $content .= <<<HTML
<tr data-aos="fade-up" data-aos-duration="1000">
    <td class="req-name"><a href="/programmes/detail/{$r['id']}">{$r['name']}</a></td>
    <td class="req-entry">{$rqs}</td>
    <td class="req-duration">{$r['duration']}</td>
</tr>
HTML;
            }
            $content .= '</tbody></table>';
        } else {
            $content .= '<p class="no-reqs">No requirements found.</p>';
        }

        $content .= '</div>'; // close container

        $head = <<<HTML
<style>
.requirements-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.requirements-title {
    font-size: 2rem;
    color: var(--amucta-blue);
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
}

.requirements-table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.requirements-table th, 
.requirements-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

.requirements-table th {
    background-color: var(--amucta-dark);
    color: #fff;
    font-weight: bold;
}

.requirements-table tr:hover {
    background-color: #f9fafb;
}

.req-name {
    font-weight: bold;
    color: var(--amucta-blue);
}

.no-reqs {
    text-align: center;
    font-size: 1.125rem;
    color: #374151;
    margin-top: 20px;
}
</style>
HTML;

        $title = "Program Entry Requirements";

        MainLayout::render($content, $head, $title);
    }

    public function apply()
    {

    }
    public function deadlines()
    {

    }
}