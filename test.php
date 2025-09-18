<?php
require_once "vendor/autoload.php";

$staff_academics = [
    ["name" => "Sr.Dr. Sophia Mbihije", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Rev. Dr. Apolinary K. Ndasi", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Dr. Daniel Dogbe", "rank" => "Lecturer", "department" => "N/A"],
    ["name" => "Dr Samuel Amoako", "rank" => "Lecturer", "department" => "N/A"],
    ["name" => "Dr Ephraim Kalanje", "rank" => "Lecturer", "department" => "N/A"],
    ["name" => "Dr. Eugen Philip", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Dr. Ildephonce Mkama", "rank" => "Senior Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Dr. Julius Nyaombo", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)GRAPHY"],
    ["name" => "Dr. Luther I. Kawiche", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Dr. Alfred Ong'ang'a", "rank" => "Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Grace Mkosamali", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Koboli Milobo", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Renata C. Nyelo", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Anna Ahmed", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Einhard Mgaya", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Clavery Kayugumi", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Triphonius Lissu", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Rainer Likongo", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Gwakisa Kaswaga", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF ARTS WITH EDUCATION (BAED)"],
    ["name" => "Andrew Kifua", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Harriet Malichi", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Fraterinus O. Mutatembwa", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Gideon D. Kaziri", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Elijah Kokse", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Filbert Zomba", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF BUSINESS ADMINISTRATION (BBA)"],
    ["name" => "Placid Komba", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF BUSINESS ADMINISTRATION (BBA)"],
    ["name" => "Patrick Chekwe", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Saikon J. Nokoren", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF BUSINESS ADMINISTRATION (BBA)"],
    ["name" => "Faustine Rwechungura", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF BUSINESS ADMINISTRATION (BBA)"],
    ["name" => "Edgar Pastory", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF BUSINESS ADMINISTRATION (BBA)"],
    ["name" => "Upendo Ulaya", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Shukuru Mukama", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Denice Salapion", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Msei Nyagani", "rank" => "Assistant Lecturer", "department" => "N/A"],
    ["name" => "Karuta Musa", "rank" => "Tutorial Assistant", "department" => "N/A"],
    ["name" => "Gaga Nidwa", "rank" => "Tutorial Assistant", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Cronel Diogenes", "rank" => "Tutorial Assistant", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Sr Martha Herman", "rank" => "Tutorial Assistant", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Jackson Manase", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Magreth Nkuba", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Ansila Nyaki", "rank" => "Assistant Lecturer", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"],
    ["name" => "Nasfath Rugimbana", "rank" => "Tutorial Assistant", "department" => "BACHELOR OF SPECIAL NEEDS EDUCATION (BEDSN)"]
];
$staff_hod = [
    ["name" => "Koboli Milobo", "department" => "Quality Assurance"],
    ["name" => "Dr. Ildephonce Mkama", "department" => "Dean of Faculty of Education and Social Sciences"],
    ["name" => "Rev. Dr. Apolinary K. Ndasi", "department" => "Director of Postgraduate Studies, Research and Consultancy"],
    ["name" => "Dr. Julius Nyaombo", "department" => "Examination Officer"],
    ["name" => "Sr. Grace Mkosamali", "department" => "Counseling"],
    ["name" => "Dr. Luther I. Kawiche", "department" => "Head of Department of Education Foundation"],
    ["name" => "Triphonius Lissu", "department" => "Head of Unit of Business Administration and Social Sciences"],
    ["name" => "Gwakisa Kaswaga", "department" => "Coordinator of Teaching Practice"],
    ["name" => "Dr. Eugen Philip", "department" => "Chief Librarian"],
    ["name" => "Fraterinus O. Mutatembwa", "department" => "Head of Department of Special Needs Education and Coodnator of Center of Inclusive Education"],
    ["name" => "Clavery Kayugumi", "department" => "Dean of Students"],
    ["name" => "Rev. Josephat Mande", "department" => "Chaplain"],
    ["name" => "Judith Mwanga", "department" => "Human Resource Management Officer"],
    ["name" => "Amatyus Mugonzibwa", "department" => "Loan Officer"],
    ["name" => "Sr. Ester Kapfizi", "department" => "Admission Officer"],
    ["name" => "Alois D Kyando", "department" => "IT Officer"],
    ["name" => "Isaya Bruno Anyingisye", "department" => "Dispensary In charge"],
    ["name" => "Paschal Charles", "department" => "Public Relation Officer"],
    ["name" => "Saikon J. Nokoren", "department" => "Legal Officer"]
];
$staff_admin = [
    ["name" => "Rev.Josephat Mande", "title" => "Chaplain"],
    ["name" => "Judith Mwanga", "title" => "Human Resources Management Officer"],
    ["name" => "Pascalina Joseph", "title" => "Assistant Accountant"],
    ["name" => "Isaya Bruno Anyingisye", "title" => "Clinical Officer"],
    ["name" => "Sr Liberatus Mhema", "title" => "Assistant Accountant"],
    ["name" => "Gaudensia Mtaki", "title" => "Cashier"],
    ["name" => "Amatyus Mugonzibwa", "title" => "Loan Officer"],
    ["name" => "Sr Ester Kapfizi", "title" => "Admission Officer"],
    ["name" => "Martha Lumambo", "title" => "Administrative Secretary"],
    ["name" => "Alois D Kyando", "title" => "IT Officer"],
    ["name" => "Daudi Mabula", "title" => "Assistant IT Officer"],
    ["name" => "Sr Victoria Mamiro", "title" => "Assistant Medical Officer"],
    ["name" => "John Lukonge", "title" => "Technical Assistant"],
    ["name" => "Emmanuel Sitta", "title" => "Technical Assistant"],
    ["name" => "Erick Massamaki", "title" => "Eletrician"],
    ["name" => "Elizabeth Sospeter", "title" => "Librarian"],
    ["name" => "Naisiriri Memruth", "title" => "Nurse"],
    ["name" => "Julian Romanus Dalika", "title" => "Fundraiser"],
    ["name" => "Justina Marwa", "title" => "Estate Manager"],
    ["name" => "Emmanuel Simon", "title" => "Assistant Librarian"],
    ["name" => "Richard Ngeze", "title" => "Assistant Librarian"],
    ["name" => "Walter Silayo", "title" => "Assistant Librarian"],
    ["name" => "Mathias Louis", "title" => "Assistant Estate Manager"],
    ["name" => "Paschal Charles", "title" => "Public Relations Officer"],
    ["name" => "Winfrida Ngoloke", "title" => "Laboratory"],
    ["name" => "Sr Catherine Masanibwa", "title" => "Nurse"],
    ["name" => "Rehema Mlimuka", "title" => "Nurse"],
    ["name" => "Moses Kabeya", "title" => "Data Clerk"],
    ["name" => "Elia Elia", "title" => "Assistant Accountant"]
];
$members_gboard = [
    [
        "name"  => "His Eminence Protase Cardinal, RUGAMBWA",
        "title" => "Chairman",
        "desc"  => "Archibishop of Tabora",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Rt Rev. Mapunda, EDWARD",
        "title" => "Member",
        "desc"  => "Bishop of Singida",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Amb. Prof Mahalu, COSTA RICKY",
        "title" => "Member",
        "desc"  => "SAUT Vice Chancellor",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Rev. Fr Kitima, CHARLES",
        "title" => "Member",
        "desc"  => "TEC Secretary General",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Prof. Rugarabamu, PASCHALIS",
        "title" => "Member",
        "desc"  => "V.C CUHAS",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Miss. Joseph, PASKALINA",
        "title" => "Member",
        "desc"  => "Busar-AMUCTA",
        "image" => "../../../../assets/staff/paskalina.jpeg"
    ],
    [
        "name"  => "Mr. Sanga, CLEMENT",
        "title" => "Member",
        "desc"  => "Representative of MoEST",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Mr Mtaki, REVOCATUS",
        "title" => "Member",
        "desc"  => "Advocate",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Rev.Prof. Asantemungu, JUVENALIS",
        "title" => "Principal AMUCTA",
        "desc"  => "Member",
        "image" => "../../../../assets/staff/asantemungu.jpg"
    ],
    [
        "name"  => "Rev. Prof. Emmanuel Wabanhu ,",
        "title" => "Member",
        "desc"  => "Deputy Principal for Administration and Finance, AMUCTA",
        "image" => "../../../../assets/staff/wabanhu.JPG"
    ],
    [
        "name"  => "AMUCTASO President,",
        "title" => "Member",
        "desc"  => "-",
        "image" => "../../../../assets/staff/default.gif"
    ]
];
$board_commitee = [
    [
        "name"  => "Rev.Prof. Asantemungu, JUVENALIS",
        "title" => "Chairman",
        "desc"  => "Principal",
        "image" => "../../../../assets/staff/asantemungu.jpg"
    ],
    [
        "name"  => "Dr. Kawiche, LUTHER",
        "title" => "Lecturer",
        "desc"  => "Dean of Faculty of Arts and Social Sciences",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Prof. Erasmus, KOMGISHA",
        "title" => "",
        "desc"  => "SAUT Representative",
        "image" => "../../../../assets/staff/default.gif"
    ],
    [
        "name"  => "Dr. Philip, EUGEN M",
        "title" => "Assistant Lecturer",
        "desc"  => "Examination Officer",
        "image" => "../../../../assets/staff/phillip.jpeg"
    ],
    [
        "name"  => "Rev. Dr. Ndasi, APOLNARY",
        "title" => "Lecturer",
        "desc"  => "HoD Special Needs, Languages and Linguistics",
        "image" => "../../../../assets/staff/ndasi.jpeg"
    ],
    [
        "name"  => "Assistant Lecturer",
        "title" => "Quality Assurance Officer",
        "desc"  => "",
        "image" => "../../../../assets/staff/lissu.jpeg"
    ],
    [
        "name"  => "Mr. Mshumbusi, EDGAR PASTORY",
        "title" => "",
        "desc"  => "Dean of Students",
        "image" => "../../../../assets/staff/mushumbushi.jpeg"
    ],
    [
        "name"  => "Fr. Mgaya, EINHARD G",
        "title" => "Assistant Lecturer",
        "desc"  => "Representative of Academic Staff",
        "image" => "../../../../assets/staff/mgaya.jpeg"
    ],
    [
        "name"  => "Sr Kapfizi, ESTHER",
        "title" => "",
        "desc"  => "Admission Officer",
        "image" => "../../../../assets/staff/kapfizi.jpeg"
    ],
    [
        "name"  => "AMUCTASO President",
        "title" => "",
        "desc"  => "Students' Government",
        "image" => "../../../../assets/staff/default.gif"
    ]
];

foreach ($board_commitee as $staff) {
    $name=trim($staff['name']);
    $db=new \Solobea\Dashboard\database\Database();
    try {
        $rg_id=5;
        $uid=72;
        $rn=trim($staff['desc']);
        $did=9;
        $uid=9;
        $eid=$db->select("select id from employee where name like '%{$name}%'")[0]['id'];
        /*if ($db->insert("employee_role",["employee_id"=>$eid, "department_id"=>$did,  "unit_id"=>$uid, "role_group_id"=>$rg_id, "role_name"=>$rn, "user_id"=>$uid])){
            echo "saved ".$name."\n";
        }else{
            echo "not saved ".$name."\n";
        }*/
    }catch (Exception $exception){
        echo "saved ".$name."\n";
    }
}