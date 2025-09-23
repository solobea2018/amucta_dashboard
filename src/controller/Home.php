<?php


namespace Solobea\Dashboard\controller;


use Solobea\Dashboard\database\Database;
use Solobea\Dashboard\view\MainLayout;

class Home
{
    public function index()
    {
        $query="select id, name from program order by level_id,department_id";
        $db=new Database();
        $program_count=Program::getCount();
        $emp_count=Employee::getCount();
        $visitorStats = Visitors::dataArray();
        $all_times=number_format($visitorStats['totals']['all_time']);
        $today=$visitorStats['totals']['today'];
        $week=$visitorStats['totals']['this_week'];
        $loc="";
        foreach ($visitorStats['top_countries'] as $lo){
            $country=$lo['country'];
            $total=$lo['total'];
            $loc.="<li>$country - $total</li>";
        }
        $urls="";
        foreach ($visitorStats['top_urls'] as $lo){
            $country=$lo['url'];
            $total=$lo['total'];
            $urls.="<p><a class='text-blue-500' href='https://amucta.ac.tz{$country}'>https://amucta.ac.tz{$country} </a> - $total</p>";
        }
        $progs=$db->select($query);
        $intro=$this->intro($db);
        $news=$this->news($db);
        $hp=$this->getRecentHomepageIntro($db);
        $h_content=htmlspecialchars_decode($hp['content']??"");
        $h_style=htmlspecialchars_decode($hp['style']??"");
        $prog_list="";
        if (!empty($progs)){
            foreach ($progs as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $prog_list.="<option value='$id'>{$name}</option>";
            }
        }
        $head=<<<kl
<link type='text/css' rel='stylesheet' href='/css/home.css'>
{$h_style}
kl;
        $content = <<<content
<!-- Background container -->
<div id="background-slider"></div>
<section class="section flex flex-row justify-center">
  <div class="text-center or-title">
    <h1 class="uni-title">Archbishop Mihayo University College of Tabora</h1>
    <a href="https://oas.amucta.ac.tz" target="_blank" class="apply-btn">🎓 Apply Now</a>
  </div>
</section>
$h_content


<section class="search-box">
  <h2>Find Your Program</h2>
  <form action="/programmes/search" method="post" class="search-form">
    <select name="course" required>
      <option value="" disabled selected>Select a course</option>
      $prog_list
    </select>
    <button type="submit">🔍 Search</button>
  </form>
</section>
$intro
$news
<section class="stats-section">
    <div class="stats-container">
      <h2 class="stats-title">AMUCTA in Numbers</h2>
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number" style="color: #007BFF;">$program_count</div>
          <p class="stat-label">Programs</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #4F46E5;">2,207</div>
          <p class="stat-label">Students</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #059669;">$emp_count</div>
          <p class="stat-label">Employees</p>
        </div>
        <div class="stat-item">
          <div class="stat-number" style="color: #7C3AED;">4,087+</div>
          <p class="stat-label">Graduates</p>
        </div>
        <div class="stat-item visitor-stat">
          <div class="stat-number">{$all_times}M</div>
          <p class="stat-label">Visitors (All-Time)</p>
          <div class="visitor-details">
            <p>📅 Today: <span style="font-weight: 600;">{$today}</span></p>
            <p>📊 This Week: <span style="font-weight: 600;">{$week}</span></p>
            <p>🌍 Top Locations:</p>
            <ul>
              {$loc}
            </ul>
          </div>
        </div>
        <div class="stat-item visitor-stat">
          <div class="visitor-details">
            <p>Most visited Pages:</p>           
              {$urls}          
          </div>
        </div>
      </div>
    </div>
  </section>
content;

        MainLayout::render($content,$head);
    }

    private function news(Database $database): string
    {
        $query="select name, file_url, created_at from attachments order by id desc limit 6";
        $attachments=$database->select($query);
        $attachments_list="";
        if (!empty($attachments)){
            foreach ($attachments as $prog) {
                $name=$prog['name'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $attachments_list.=<<<atta
            <li class="attachment-item">
                <h3 class="attachment-title">{$name}</h3>
                <div class="attachment-meta">
                    <a href="{$prog['file_url']}" class="download-link">📁 Download</a>
                    <span>|</span>
                    <span class="date">📅 {$date}</span>
                </div>
            </li>
atta;
            }
        }

        $query="select id, feature_image, name, content, created_at, expire, category, attachment from news order by created_at desc limit 4";
        $news=$database->select($query);
        $news_list="";
        if (!empty($news)){
            foreach ($news as $prog) {
                $name=$prog['name'];
                $feature_image=$prog['feature_image'];
                $date = date("F d, Y", strtotime($prog['created_at']));
                $content = $prog['content'];
                $expire  = $prog['expire'];
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="new-icon">';
                }
                $shortContent = mb_substr(strip_tags($content), 0, 100);
                if (strlen(strip_tags($content)) > 100) {
                    $shortContent .= "...";
                }
                $attachment =$prog['attachment']??"#";
                $news_list.=<<<atta
            <div class="news-item">
                <img src="$feature_image" class="news-img" alt="News">
                <div class="news-content">
                    <div class="news-title" onclick="popHtml('$name','$content')">{$name} $img</div>
                    <p class="news-desc">
                        {$shortContent} <a href="$attachment" class="read-more">Read More →</a>
                    </p>
                    <p class="news-date">📅 Posted on: {$date}</p>
                </div>
            </div>
atta;
            }
        }

        return<<<news
<div class="news-section">
    <!-- Latest Announcements -->
    <div class="news-secion-item">
        <h2 class="section-title">Download Center</h2>
        <ul class="attachments-list">
            $attachments_list
        </ul>
        <a href="/attachment/all" class="view-more">View More</a>
    </div>

    <!-- Latest News -->
    <div class="news-secion-item">
        <h2 class="section-title">Latest News</h2>
        <div class="news-list">
            $news_list
        </div>
        <div class="read-more-container">
            <a href="/news/all" class="read-more-btn">Read More News</a>
        </div>
    </div>
</div>
news;
    }
    private function intro(Database $database): string
    {
        $query="select id, name, start_date, end_date,location,feature_image from events order by start_date desc limit 4";
        $events=$database->select($query);
        $events_list="";
        if (!empty($events)){
            foreach ($events as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $feature_image=$prog['feature_image']??'https://www.heslb.go.tz/assets/css/assets_22/images/new.svg';
                $date = date("F d, Y", strtotime($prog['start_date']));

                $expire  = $prog['end_date']??$date;
                $img     = "";
                if (strtotime(date("Y-m-d")) <= strtotime($expire)) {
                    $img = '<img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="event-new">';
                }
                $location =$prog['location']??"";
                $events_list.=<<<atta
            <li class="event-item">
                <div class="event-flex">
                    <img src="$feature_image" alt="event" class="event-img">
                    <div>
                        <a href="/events/detail/$id" class="event-title">{$name} - {$location}</a>
                        <div class="event-meta">
                            <i class="fa fa-clock-o"></i>
                            <span>{$date}</span>
                            $img
                        </div>
                    </div>
                </div>
                <hr class="event-divider">
            </li>
atta;
            }
        }

        return <<<intro
<div class="intro-section">
    <div class="intro-container">
        <div class="intro-grid">

            <!-- CEO's Remarks -->
            <div class="intro-col">
                <h3 class="intro-heading">AMUCTA's Remarks</h3>
                <div class="remarks">
                    <img src="/assets/images/asantemungu.jpg" alt="CEO" class="remarks-img">
                    <div>
                        <p class="remarks-text">
                            The Archbishop Mihayo University College of Tabora (AMUCTA) is a constituent college of St. Augustine University of Tanzania (SAUT). AMUCTA is an independent higher-learning institution governed by the Board of Trustees and the University Council under the Catholic Bishops of Tanzania (Tanzania Episcopal Conference).
                        </p>
                        <a href="/about/message" class="link-primary">Read more</a>
                    </div>
                </div>
            </div>

            <!-- News & Events -->
            <div class="intro-col">
                <h3 class="intro-heading">News & Events</h3>
                <ul class="events-list">
                    $events_list
                    <li>
                        <a href="/events/all" class="link-primary">Read More</a>
                    </li>
                </ul>
            </div>

            <!-- Shortcut Links -->
            <div class="intro-col">
                <h3 class="intro-heading">Shortcut Links</h3>
                <ul class="shortcuts">
                    <li>
                        <a href="https://oas.amucta.ac.tz" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Confirm Application
                        </a>
                        <a href="/assets/files/AMUCTA_JOIN_INSTRUCTION.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Joining Instruction 2025/2026
                        </a>
                        <a href="https://amucta.ac.tz/assets/files/Prospectus_2022-2023.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Prospectus
                        </a>
                        <a href="/assets/files/Fee_structure_2025-2026.pdf" class="shortcut-link">
                            <i class="fa fa-chevron-circle-right"></i>
                            Fee Structure 2025-2026
                        </a>                      
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
intro;
    }

    function getRecentHomepageIntro(Database $db): array
    {
        $sql = "
        SELECT content,style
        FROM homepage_intro
        WHERE deadline > NOW()
        ORDER BY created_at DESC
        LIMIT 1
    ";

        $row = $db->select($sql);

        if ($row && isset($row[0]['content'])) {
            return $row[0];
        }

        return [];
    }


}