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
        $progs=$db->select($query);
        $intro=$this->intro();
        $news=$this->news();
        $prog_list="";
        if (!empty($progs)){
            foreach ($progs as $prog) {
                $id=$prog['id'];
                $name=$prog['name'];
                $prog_list.="<option value='$id'>{$name}</option>";
            }
        }
        $head='';
        $content=<<<content
<section class="bg-white pt-16 pb-2 md:pt-20  px-4 md:px-10">
        <div class="w-full mx-auto items-center">

            <!-- Caption & Button -->
            <div class="text-center">
                <h1 class="text-2xl md:text-4xl font-bold text-amucta-blue">Archbishop Mihayo University College of Tabora</h1>

                <a href="https://oas.amucta.ac.tz"
                   target="_blank"
                   class="mt-1 inline-block bg-[#00BFFF] text-white font-semibold px-4 py-2 rounded-xl shadow-md hover:shadow-cyan-400/50 hover:scale-105 transition-all duration-300 animate-pulse">
                    🎓 Apply Now
                </a>
            </div>

        </div>
    </section>
<section class="mx-auto max-w-4xl px-4">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#00ccff] via-[#ffff00] to-[#83e59a] p-px shadow-2xl">
        <div class="relative rounded-3xl bg-black/60 px-6 py-10 sm:px-10">
            <!-- Top badge -->
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                Second Window • Bachelor Applicants
            </div>

            <!-- Headline -->
            <h2 class="mt-5 text-3xl font-extrabold leading-tight text-white sm:text-4xl">
                Admissions <span class="underline decoration-white/40 underline-offset-4">OPEN</span> <span class="animate-pulse">NOW</span>
            </h2>

            <!-- Subcopy -->
            <p class="mt-3 max-w-2xl text-base text-white/90 sm:text-lg">
                Missed the first call? This is your moment. Limited slots, rolling review—earlier submissions get priority.
                Secure your spot before the window closes.
            </p>

            <!-- Highlights -->
            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-white/10 p-4 text-white/90 ring-1 ring-white/15">
                    <p class="font-semibold">Diploma Programmes</p>
                    <p class="font-semibold">Bachelor Programmes</p>
                    <p class="font-semibold">Postgraduate Programmes</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 text-white/90 ring-1 ring-white/15">
                    <p class="text-sm">Mode</p>
                    <p class="text-lg font-semibold">Online Application</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 text-white/90 ring-1 ring-white/15">
                    <p class="text-sm">Status</p>
                    <p class="text-lg font-extrabold tracking-wide">OPEN</p>
                </div>
            </div>

            <!-- Deadline -->
            <div class="mt-6 flex flex-wrap items-center gap-3 text-white/90">
        <span class="relative inline-flex h-2.5 w-2.5">
          <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-yellow-300 opacity-75"></span>
          <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-yellow-300"></span>
        </span>
                <p class="text-sm">
                    Closes: <span class="font-semibold">September 21, 2025</span> • Don’t wait—applications are reviewed as they arrive.
                </p>
            </div>

            <!-- Button slot -->
            <div class="mt-7">
                <!-- Place your Apply button here -->
            </div>

            <!-- Subtle corner glow -->
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
        </div>
    </div>

    <!-- Skinny banner -->
    <div class="mt-6 rounded-2xl bg-gradient-to-r from-[#00ccff] to-[#ffff00] p-4 text-center text-black shadow-lg ring-1 ring-black/10">
        <strong>Second Application Window is OPEN</strong> — limited seats, submit early for priority review.
        <span class="ml-2 rounded-full bg-black/10 px-2 py-0.5 text-xs">September 21, 2025</span>
    </div>
</section>
<section class="w-full py-10 px-4">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-xl text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Find Your Program</h2>

            <form action="/programmes/search" method="post"
                  class="flex flex-col md:flex-row items-center gap-4 justify-center">
                <select
                        name="course"
                        required
                        class="w-full md:w-auto px-5 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 text-gray-700">
                    <option value="" disabled selected>Select a course</option>
                    $prog_list
                </select>

                <button
                        type="submit"
                        class="bg-amucta-blue hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-300">
                    🔍 Search
                </button>
            </form>
        </div>
    </section>
$intro
$news

content;

        MainLayout::render($content,$head);
    }

    private function news()
    {
        return<<<news
<div class="grid md:grid-cols-2 gap-6 p-4 bg-gray-100">
    <!-- Latest Announcements -->
    <div>
        <h2 class="text-xl font-semibold border-b-2 border-blue-600 pb-2 mb-4">Download Center</h2>
        <ul class="space-y-4">
            <li *ngFor="let item of homeModel.news | slice:0:(showAllNews ? homeModel.news.length : 5)">
                <h3 class="text-blue-900 font-medium hover:underline cursor-pointer">
                    {{ item.name }}
                </h3>
                <div class="text-sm text-gray-500 flex items-center space-x-2">
                    <a [href]="item.download" class="text-yellow-600 font-medium">
                        {{ item.downloadLabel }}
                    </a>
                    <span>|</span>
                    <span>{{ item.date }}</span>
                </div>
            </li>
        </ul>

        <!-- View More / View Less Button -->
        <button (click)="toggleNews()" class="mt-4 text-blue-700 font-semibold hover:underline">
            {{ showAllNews ? 'View Less' : 'View More' }}
        </button>
    </div>


    <!-- Latest News -->
    <div>
        <h2 class="text-xl font-semibold border-b-2 border-blue-600 pb-2 mb-4">Latest News</h2>
        <div class="space-y-6">
            <div class="flex gap-4" *ngFor="let news of newsList | slice:0:(showAllNews ? newsList.length : 4)">
                <img [src]="news.image" class="w-24 h-24 object-cover rounded" alt="News">
                <div>
                    <div class="font-bold flex flex-row text-blue-800 hover:underline cursor-pointer">{{ news.title }}
                        <img src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="w-6 h-6">
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ news.summary }} <a [href]="news.link" class="text-blue-600">Read More →</a>
                    </p>
                    <p class="text-sm text-gray-500 mt-1">📅 Posted on: {{ news.date }}</p>
                </div>
            </div>
        </div>

        <!-- Read More Button -->
        <div class="mt-6 text-center">
            <button (click)="togglePictureNews()" class="text-blue-700 font-semibold hover:underline">
                {{ showAllPictureNews ? 'View Less' : 'Read More News' }}
            </button>
        </div>
    </div>

</div>
news;

    }

    private function intro()
    {
        return <<<intro
<div class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- CEO's Remarks -->
            <div class="lg:w-1/3">
                <h3 class="text-xl font-bold mb-2 border-b-2 border-blue-500 inline-block">AMUCTA's Remarks</h3>
                <div class="flex flex-col sm:flex-row gap-4 mt-4">
                    <img src="/assets/images/asantemungu.jpg" alt="CEO" class="w-32 h-auto object-cover rounded shadow">
                    <div>
<!--                        <p class="text-gray-700 mb-2">{{homeModel.welcomeNote}}</p>-->
                        <p class="text-gray-600 text-sm">
                            The Archbishop Mihayo University College of Tabora (AMUCTA) is a constituent college of St. Augustine University of Tanzania (SAUT). AMUCTA is an independent higher-learning institution governed by the Board of Trustees and the University Council under the Catholic Bishops of Tanzania (Tanzania Episcopal Conference).
                        </p>
                        <a href="/about" class="text-blue-600 font-semibold mt-2 inline-block">Read more</a>
                    </div>
                </div>
            </div>

            <!-- News & Events -->
            <div class="lg:w-1/3">
                <h3 class="text-xl font-bold mb-2 border-b-2 border-blue-500 inline-block">News & Events</h3>
                <ul class="space-y-4 mt-4">
                    <li *ngFor="let item of newsEvents | slice:0:5">
                        <div class="flex items-start gap-3">
                            <img src="https://www.heslb.go.tz/assets/css/assets_22/images/new.svg" alt="new" class="w-10">
                            <div>
                                <a [href]="item.link" class="text-sm font-medium text-gray-800 hover:text-blue-600 block">
                                    {{ item.title }}
                                </a>
                                <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                    <i class="fa fa-clock-o"></i>
                                    <span>{{ item.date }}</span>
                                    <img *ngIf="item.hasNewBadge" src="https://www.heslb.go.tz/assets/images/new.gif" alt="new" class="w-6">
                                </div>
                            </div>
                        </div>
                        <hr class="mt-2">
                    </li>

                    <!-- Read More Toggle -->
                    <li>
                        <a routerLink="/target-page" class="inline-block  text-amucta-blue font-semibold hover:bg-blue-700">
                            Read More
                        </a>
                    </li>
                </ul>
            </div>


            <!-- Shortcut Links -->
            <div class="lg:w-1/3">
                <h3 class="text-xl font-bold mb-2 border-b-2 border-blue-500 inline-block">Shortcut Links</h3>
                <ul class="mt-4 space-y-3 text-sm text-gray-800">
                    <li *ngFor="let link of shortcutLinks">
                        <a [href]="link.url" target="_blank" class="hover:text-blue-600 flex items-start gap-2">
                            <i class="fa fa-chevron-circle-right mt-1 text-blue-500"></i>
                            {{ link.title }}
                        </a>
                    </li>
                </ul>
            </div>


        </div>
    </div>
</div>
intro;

    }
}