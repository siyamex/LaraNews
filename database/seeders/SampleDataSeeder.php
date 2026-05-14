<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Enums\PostType;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Suppress email sending during seeding
        \Illuminate\Support\Facades\Config::set('mail.default', 'array');

        // ── 1. Fix categories: enable show_in_menu ────────────────────────
        Category::whereNull('parent_id')->update(['show_in_menu' => true]);
        $this->command->info('✅ Categories updated (show_in_menu = true)');

        // ── 2. Extra users ────────────────────────────────────────────────
        $editor = User::firstOrCreate(['email' => 'editor@laranews.test'], [
            'name' => 'Sarah Ahmed', 'username' => 'sarah.ahmed',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), 'locale' => 'en', 'is_active' => true,
        ]);
        $editor->assignRole('editor');

        $journalist = User::firstOrCreate(['email' => 'journalist@laranews.test'], [
            'name' => 'Mohamed Rashid', 'username' => 'mo.rashid',
            'password' => Hash::make('password'),
            'email_verified_at' => now(), 'locale' => 'dv', 'is_active' => true,
        ]);
        $journalist->assignRole('journalist');

        $admin = User::where('email', 'admin@laranews.test')->first();
        $this->command->info('✅ Users created');

        // ── 3. Tags ───────────────────────────────────────────────────────
        $tagsData = [
            ['en' => 'Breaking News',   'dv' => 'ހަބަރު ކުއްލި', 'slug' => 'breaking-news'],
            ['en' => 'Politics',        'dv' => 'ސިޔާސަތު',       'slug' => 'politics-tag'],
            ['en' => 'Economy',         'dv' => 'އިޤްތިޞާދު',    'slug' => 'economy'],
            ['en' => 'Climate',         'dv' => 'ތިމާވެށި',       'slug' => 'climate'],
            ['en' => 'Tourism',         'dv' => 'ފަތުރުވެރިކަން', 'slug' => 'tourism'],
            ['en' => 'Fisheries',       'dv' => 'ދޯނިފަހަރު',    'slug' => 'fisheries'],
            ['en' => 'Education',       'dv' => 'ތައުލީމު',       'slug' => 'education-tag'],
            ['en' => 'Health',          'dv' => 'ސިއްހަތު',       'slug' => 'health-tag'],
            ['en' => 'Technology',      'dv' => 'ތަރައްގީ',       'slug' => 'tech-tag'],
            ['en' => 'Sports',          'dv' => 'ކުޅިވަރު',       'slug' => 'sports-tag'],
            ['en' => 'Environment',     'dv' => 'ތިމާވެށި',       'slug' => 'environment'],
            ['en' => 'Election',        'dv' => 'އިންތިޚާބު',    'slug' => 'election'],
        ];
        $tags = [];
        foreach ($tagsData as $i => $td) {
            $tag = Tag::firstOrCreate(['slug' => $td['slug']], [
                'slug' => $td['slug'],
                'is_featured' => $i < 6,
                'posts_count' => rand(5, 80),
            ]);
            foreach (['en', 'dv'] as $locale) {
                $tag->translations()->firstOrCreate(['locale' => $locale], [
                    'locale' => $locale, 'name' => $td[$locale], 'slug' => $td['slug'],
                ]);
            }
            $tags[$td['slug']] = $tag;
        }
        $this->command->info('✅ Tags created');

        // ── 4. Load categories ────────────────────────────────────────────
        $cats = Category::with('translations')->get()->keyBy(fn($c) => $c->getSlugForLocale('en'));

        // ── 5. Posts ──────────────────────────────────────────────────────
        $postsData = [
            // ─── HERO SLIDER (is_featured=true, first 6 shown in slider) ───
            [
                'cat' => 'national', 'type' => 'article', 'featured' => true, 'breaking' => true, 'trending' => true,
                'views' => 45200, 'minutes_ago' => 30, 'user' => $admin,
                'en' => ['title' => 'Government Announces Major Infrastructure Investment Plan for 2026',
                         'slug'  => 'government-infrastructure-investment-2026',
                         'excerpt' => 'The Maldivian government has unveiled an ambitious infrastructure investment plan worth MVR 2.5 billion aimed at developing atolls across the country.',
                         'content' => $this->article('The Maldivian government revealed its most ambitious infrastructure plan to date on Wednesday, allocating MVR 2.5 billion for development projects spanning 23 inhabited islands. President Ibrahim Mohamed Solih announced the initiative during a press conference at the President\'s Office, highlighting key projects including a new inter-atoll ferry network, upgraded school facilities, and expanded healthcare centers. "This investment will transform daily life for thousands of Maldivians living in the outer atolls," said the President. The plan includes construction of modern harbors in Addu City and Fuvahmulah, a new power grid for Noonu Atoll, and fast-fiber internet connections to 15 previously unconnected islands. Opposition parties have welcomed the announcement but called for transparent tendering processes. Implementation is expected to begin in Q3 2026 with completion targets set for 2029.')],
                'dv' => ['title' => 'ސަރުކާރުން 2026 ވަނަ އަހަރަށް ބޮޑު ތަރައްޤީ ޕްލޭނެއް އިއުލާންކޮށްފި',
                         'slug'  => 'sarukaar-tharaqqee-2026',
                         'excerpt' => 'ދިވެހި ސަރުކާރުން 2.5 ބިލިއަން ރުފިޔާގެ ތަރައްޤީ ޕްލޭނެއް ހާމަ ކޮށްފިއެވެ.'],
            ],
            [
                'cat' => 'international', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => true,
                'views' => 38700, 'minutes_ago' => 90, 'user' => $editor,
                'en' => ['title' => 'UN Climate Summit Reaches Historic Agreement on Ocean Protection',
                         'slug'  => 'un-climate-summit-ocean-protection-agreement',
                         'excerpt' => 'World leaders at the UN Climate Summit have signed a landmark treaty to protect 30% of the world\'s oceans by 2030, with small island nations playing a pivotal role.',
                         'content' => $this->article('Representatives from 195 countries signed the landmark High Seas Treaty in Geneva on Tuesday, pledging to protect 30% of international waters by 2030. The Maldives, along with other small island developing states, played a pivotal role in negotiations. Foreign Minister Abdulla Shahid called it "a victory for our survival." The treaty establishes a new body under the UN Convention on the Law of the Sea to manage protected areas, share marine genetic resources, and conduct environmental impact assessments for deep-sea mining. Critics from the fossil fuel industry argue the timeline is too aggressive, while environmental groups say it does not go far enough. The Intergovernmental Panel on Climate Change estimates that properly managed ocean reserves could sequester up to 1.8 billion tonnes of carbon annually.')],
                'dv' => ['title' => 'އދ.ގެ 气候 ސަމިޓުން ކަނޑު ހިމާޔަތް ކުރަން ތާރީހީ އެއްބަސްވުމެއް',
                         'slug'  => 'un-climate-kadhu-hifaazath',
                         'excerpt' => 'ދިވެހިރާއްޖެ ހިމެނޭ ގޮތަށް 195 ގައުމަކުން ކަނޑު ހިމާޔަތް ކުރަން ތާރީހީ މުއާހަދާއެއްގައި ސޮއިކޮށްފިއެވެ.'],
            ],
            [
                'cat' => 'politics', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => true,
                'views' => 29100, 'minutes_ago' => 180, 'user' => $journalist,
                'en' => ['title' => 'Parliamentary Session Opens with Heated Debate Over Budget Allocation',
                         'slug'  => 'parliament-budget-debate-session-2026',
                         'excerpt' => 'The People\'s Majlis resumed its spring session with a fiery debate over the supplementary budget, with opposition MPs demanding greater fiscal transparency.',
                         'content' => $this->article('The People\'s Majlis convened its spring session on Monday with an immediate clash over the government\'s supplementary budget request of MVR 800 million. Opposition parliamentarians, led by PPM group leader Ahmed Saleem, challenged line items they claimed lacked proper justification, particularly a MVR 150 million allocation labeled as "contingency operations." Finance Minister Ibrahim Ameer defended the request, citing unexpected costs from Cyclone Moana recovery and rising global commodity prices. The session descended into brief disorder when microphones were switched off during an opposition speech, prompting a procedural complaint. Speaker Mohamed Aslam adjourned proceedings for 30 minutes. The debate is expected to continue through the week with a vote scheduled for Thursday.')],
                'dv' => ['title' => 'ބަޖެޓު ވާހަކައިގައި ރައްޔިތުންގެ މަޖިލިސް ހިލޭ',
                         'slug'  => 'majlis-budget-vibaa',
                         'excerpt' => 'ރައްޔިތުންގެ މަޖިލީހުގެ 봄 ސެޝަން ބަޖެޓާ ބެހޭ ވާދަވެރި ބަހުސަކުން ހުޅުވިއްޖެ.'],
            ],
            [
                'cat' => 'business', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => true,
                'views' => 22400, 'minutes_ago' => 300, 'user' => $editor,
                'en' => ['title' => 'Tourism Revenue Hits Record High as Visitor Numbers Surge in Q1',
                         'slug'  => 'tourism-record-revenue-q1-2026',
                         'excerpt' => 'The Maldives recorded its highest ever first-quarter tourism revenue, with arrivals jumping 18% year-on-year driven by growth from European and East Asian markets.',
                         'content' => $this->article('The Maldives Tourism Ministry announced record-breaking first-quarter statistics on Friday, with 612,000 tourist arrivals generating USD 1.2 billion in direct revenue between January and March 2026. The figure represents an 18% increase over the same period last year. Europe accounted for 41% of arrivals, with the UK, Germany, and Russia leading source markets. East Asian arrivals from China and South Korea surged 34% following the expansion of direct flight connections. Average length of stay increased to 7.4 nights, up from 6.9 nights in 2025. Industry analysts attribute the growth to successful destination marketing campaigns and a growing portfolio of luxury properties in emerging atolls. The government has set an annual target of 2.5 million arrivals for 2026.')],
                'dv' => ['title' => 'ފަތުރުވެރިކަމުން ލިބޭ ފައިސާ ރެކޯޑަށް',
                         'slug'  => 'tourism-record-faisa',
                         'excerpt' => 'ފުރަތަމަ ކުއާޓަރ ތެރޭ ފަތުރުވެރިކަމުުން ލިބޭ ފައިސާ ރެކޯޑް ވެއްޖެ.'],
            ],
            [
                'cat' => 'sports', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => false,
                'views' => 18900, 'minutes_ago' => 420, 'user' => $journalist,
                'en' => ['title' => 'Maldives Football Team Qualifies for SAFF Championship Semi-Finals',
                         'slug'  => 'maldives-football-saff-semi-final',
                         'excerpt' => 'The Maldives national football team secured a 2-1 victory over Bangladesh to advance to the SAFF Championship semi-finals for the first time in eight years.',
                         'content' => $this->article('A stunning late header from Ibrahim Waheed "Beko" in the 87th minute secured a historic 2-1 win for the Maldives against Bangladesh at the SAFF Championship group stage in Colombo on Saturday. The result means the Red Snappers advance to the semi-finals for the first time since 2018. Head coach Ismail Mahfoodh praised the team\'s resilience: "We showed incredible heart tonight. The boys never gave up." Waheed\'s winner sparked wild celebrations among the 3,000 Maldivian fans who had traveled to Sri Lanka for the tournament. The semi-final draw will be held on Sunday, with the Maldives set to face either India or Nepal. Players including captain Ali Fasir and goalkeeper Mohamed Faisal received particular praise from commentators for outstanding performances.')],
                'dv' => ['title' => 'ދިވެހި ޓީމް ސެމީ ފައިނަލަށް',
                         'slug'  => 'divehi-team-semi-final',
                         'excerpt' => 'ސާފް ޗެމްޕިއަންޝިޕްގައި ދިވެހި ރާއްޖޭ ސެމީ ފައިނަލަށް ދެވިއްޖެ.'],
            ],
            [
                'cat' => 'education', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => false,
                'views' => 14300, 'minutes_ago' => 600, 'user' => $editor,
                'en' => ['title' => 'Ministry Launches Free Coding Education Program for All Schools',
                         'slug'  => 'ministry-free-coding-education-program',
                         'excerpt' => 'The Ministry of Education has announced a nationwide coding curriculum to be introduced in all schools from Grade 4, aiming to prepare students for the digital economy.',
                         'content' => $this->article('Education Minister Dr. Ismail Shafeeu unveiled the "Code Maldives" initiative on Thursday, a comprehensive program to teach programming, robotics, and computational thinking to students nationwide starting from Grade 4. The program, developed in partnership with tech giants Google and Microsoft, will be rolled out in the 2026-2027 academic year. An initial cohort of 500 teachers will receive training over the summer months. Tablets and coding kits will be distributed to schools free of charge. Minister Shafeeu noted that preparing young Maldivians for digital careers is essential for economic diversification. The program cost of MVR 45 million will be funded through a mix of government budget and development aid from the World Bank.')],
                'dv' => ['title' => 'ހުރިހާ ސުކޫލެއްގައި ކޯޑިން ކިޔަވައިދިނުން ފަށަނީ',
                         'slug'  => 'coding-education-school',
                         'excerpt' => 'ތައުލީމީ ވުޒާރާ ހިލޭ ކޯޑިން ތަރުބިއްޔަތު ޕްރޮގްރާމެއް ތައާރަފް ކޮށްފި.'],
            ],
            // ─── Additional Featured for the "featured" grid (skip(6).take(4)) ───
            [
                'cat' => 'health', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => true,
                'views' => 11500, 'minutes_ago' => 720, 'user' => $admin,
                'en' => ['title' => 'New Hospital Wing Opens in Addu City with Advanced Cancer Treatment Facilities',
                         'slug'  => 'addu-city-hospital-cancer-treatment',
                         'excerpt' => 'A state-of-the-art cancer treatment wing at the Addu City hospital opened its doors on Monday, bringing advanced oncology services to the southernmost Maldives for the first time.',
                         'content' => $this->article('The newly completed Radiation Oncology Unit at Addu City Hospital was inaugurated by Health Minister Ahmed Naseem in a ceremony attended by hundreds of local residents and visiting medical professionals. The MVR 180 million facility includes a linear accelerator, PET-CT scanner, and 24 dedicated oncology beds. Previously, cancer patients from southern atolls had to travel to Malé or seek treatment abroad, often at prohibitive expense. "This is a life-changing moment for families in the south," said Dr. Naseem. The unit is staffed by three oncologists trained in India and the UK, supported by ten specialized nurses. Health authorities expect to treat approximately 200 patients annually at full capacity.')],
                'dv' => ['title' => 'އައްޑޫ ސިޓީ ހޮސްޕިޓަލްގައި ކެންސަރ ވޯޑް ހުޅުވައިފި',
                         'slug'  => 'addu-hospital-cancer-ward',
                         'excerpt' => 'ދެކުނުގައި ކެންސަރ ފަރުވާ ދެވޭ ވޯޑެއް ހުޅުވިއްޖެ.'],
            ],
            [
                'cat' => 'technology', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => true,
                'views' => 9800, 'minutes_ago' => 900, 'user' => $journalist,
                'en' => ['title' => 'Maldives Launches National Digital ID System with Biometric Verification',
                         'slug'  => 'maldives-national-digital-id-biometric',
                         'excerpt' => 'The government has officially launched the National Digital Identity platform, enabling citizens to access over 200 government services through a single secure biometric-verified account.',
                         'content' => $this->article('The Ministry of Technology and Innovation launched the eagerly anticipated National Digital ID (NDID) system on Tuesday, marking a major milestone in the country\'s e-governance journey. Citizens can now use facial recognition and fingerprint verification to access services ranging from passport renewal to business registration through the gov.mv platform. The system was developed by a local firm, Dhiraagu Digital Solutions, in collaboration with Estonian e-governance experts. Minister of Technology Ibrahim Nashid called it "the most significant modernization of public services in Maldivian history." Early adoption has been rapid, with 50,000 citizens registering in the first 48 hours. The system will eventually replace physical ID cards for most government transactions.')],
                'dv' => ['title' => 'ޑިޖިޓަލް ދިވެހި ރައްވެހިކަމުގެ ކާޑު ލޯންޗް ކޮށްފި',
                         'slug'  => 'digital-id-launch',
                         'excerpt' => 'ސަރުކާރުން ޑިޖިޓަލް ދިވެހި ރައްވެހިކަމުގެ ނިޒާމް ތައާރަފް ކޮށްފި.'],
            ],
            [
                'cat' => 'national', 'type' => 'article', 'featured' => true, 'breaking' => true, 'trending' => false,
                'views' => 31000, 'minutes_ago' => 15, 'user' => $editor,
                'en' => ['title' => 'Flash Floods Hit Three Northern Atolls After Unseasonal Heavy Rain',
                         'slug'  => 'flash-floods-northern-atolls-rain',
                         'excerpt' => 'Emergency services have been deployed to Raa, Baa, and Noonu atolls following flash flooding caused by 12 hours of continuous rainfall, with over 400 families affected.',
                         'content' => $this->article('Emergency management teams from the National Disaster Management Authority were dispatched to three northern atolls early Wednesday morning after overnight flash floods inundated homes and roads. Raa Atoll Ungoofaaru, Baa Atoll Eydhafushi, and Noonu Atoll Manadhoo reported the worst flooding, with water levels reaching 60 centimetres in some areas. National Disaster Management Authority spokesperson Ali Hassan said 412 families have been registered as affected, with 67 households displaced to emergency shelters. The floods were caused by an unusual weather system bringing 280mm of rain in 12 hours — roughly twice the monthly average. The President has directed the Ministry of Housing to assess structural damage and expedite repairs. MNDF teams are pumping floodwater from affected areas.')],
                'dv' => ['title' => 'ވިއްސާރައިގައި ތިން އަތޮޅު ފެތިއްޖެ',
                         'slug'  => 'viiyhsaara-atoll-fethijje',
                         'excerpt' => 'ތިން އަތޮޅަށް ފެންބޮޑުވެ 400 ހަށި ހިލޭ ކޮށްފި.'],
            ],
            [
                'cat' => 'international', 'type' => 'article', 'featured' => true, 'breaking' => false, 'trending' => false,
                'views' => 8200, 'minutes_ago' => 1440, 'user' => $admin,
                'en' => ['title' => 'India and Maldives Sign Comprehensive Economic Partnership Agreement',
                         'slug'  => 'india-maldives-economic-partnership-agreement',
                         'excerpt' => 'The two neighbouring countries have signed a wide-ranging economic partnership covering trade, healthcare, infrastructure, and digital connectivity worth an estimated USD 500 million.',
                         'content' => $this->article('India and the Maldives signed the India-Maldives Comprehensive Economic Partnership Agreement (IMCEPA) in a ceremony at Rashtrapati Bhavan in New Delhi on Monday, marking the most significant bilateral economic deal in recent history. The agreement covers preferential trade terms, joint infrastructure projects, medical tourism, and a digital payments corridor linking UPI to the Maldives payment ecosystem. Prime Minister Modi described it as "a partnership rooted in friendship and shared prosperity," while President Solih called it "a foundation for the next generation of bilateral relations." Key components include a 500 MW submarine power cable, joint fisheries processing zones, and an Indian scholarship program for 2,000 Maldivian students annually. Implementation will be overseen by a joint ministerial committee meeting quarterly.')],
                'dv' => ['title' => 'ހިންދުސްތާނާ ދިވެހިރާއްޖެ ތަރިން ދިމާ ޕާޓްނަރޝިޕް',
                         'slug'  => 'india-maldives-partnership',
                         'excerpt' => 'ދިވެހިރާއްޖެ ހިންދުސްތާން ދެ ގައުމަށްވެސް ފައިދާ ހުރި ތަރިން ދިމާ ހެދިއްޖެ.'],
            ],
            // ─── Regular Articles ───────────────────────────────────────────
            [
                'cat' => 'national', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => true,
                'views' => 5500, 'minutes_ago' => 1800, 'user' => $journalist,
                'en' => ['title' => 'Male City Council Approves New Waste Management Zones',
                         'slug'  => 'male-city-waste-management-zones',
                         'excerpt' => 'The Malé City Council has approved a new zoning plan that divides the capital into four dedicated waste management districts to improve collection efficiency.',
                         'content' => $this->article('Malé City Council passed the Integrated Solid Waste Management Plan by 11 votes to 3 in Monday\'s session, introducing four designated waste zones each served by dedicated collection teams and vehicles. The plan, developed with Danish environmental consultancy Ramboll, aims to reduce uncollected waste by 60% and increase recycling rates from the current 8% to 35% by 2028. Zone captains will be appointed for each district with direct accountability to the Council. Digital tracking chips embedded in waste bins will monitor collection frequency. Residents will be required to separate organic, recyclable, and general waste starting October 2026. Violators face fines of MVR 500 for first offences.')],
                'dv' => ['title' => 'ކުނިވަށިގަނޑު ބެލެހެއްޓުމަށް ހާއްސަ ޒޯން',
                         'slug'  => 'male-city-waste-zone',
                         'excerpt' => 'ކ.މާލެ ސިޓީ ކައުންސިލުން ކުނި ވަށިގަނޑު ބެލެހެއްޓުމަށް ހަ ހ ޒޯން ހަދައިފި.'],
            ],
            [
                'cat' => 'business', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => true,
                'views' => 4200, 'minutes_ago' => 2100, 'user' => $editor,
                'en' => ['title' => 'BML Reports 28% Increase in Net Profit for Financial Year 2025',
                         'slug'  => 'bml-profit-increase-2025',
                         'excerpt' => 'Bank of Maldives has reported a record net profit of MVR 1.48 billion for the financial year 2025, driven by strong loan growth and improved asset quality.',
                         'content' => $this->article('Bank of Maldives (BML) announced a net profit of MVR 1.48 billion for the year ended December 2025, a 28% increase over the previous year. The bank attributed strong performance to a 22% growth in its loan portfolio, particularly housing loans and SME financing. Non-performing loans fell to 3.1%, the lowest in a decade. CEO Karl Stumke praised the bank\'s digital transformation journey, noting that 78% of transactions are now conducted digitally. BML also expanded its ATM network to all inhabited islands for the first time. The Board has proposed a dividend of MVR 18 per share. The bank\'s total assets reached MVR 42 billion, cementing its position as the country\'s largest financial institution by a significant margin.')],
                'dv' => ['title' => 'ބީ.އެމ.އެލް ފައިދާ 28% ބޮޑު',
                         'slug'  => 'bml-profit-28-percent',
                         'excerpt' => 'ބޭންކް އޮފް މޯލްޑިވްސްގެ ސާފު ފައިދާ 2025 ވަނަ ތާ 28% ބޮޑު.'],
            ],
            [
                'cat' => 'politics', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 3100, 'minutes_ago' => 2880, 'user' => $admin,
                'en' => ['title' => 'Election Commission Sets Date for By-Elections in Three Constituencies',
                         'slug'  => 'election-commission-by-elections-2026',
                         'excerpt' => 'The Elections Commission has announced that by-elections will be held on 15 July 2026 for parliamentary seats vacated in Hulhudhuffaaru, Gemanafushi, and Thinadhoo North.',
                         'content' => $this->article('The Elections Commission of the Maldives announced Wednesday that three parliamentary by-elections will take place simultaneously on July 15, 2026. The seats became vacant following the disqualification of two MPs and the resignation of a third. Voter registration for the affected constituencies closed on May 1. The commission has assigned 28 ballot boxes across the three atolls and will deploy 140 election officials. Campaign periods will run from June 20 to July 14, with a mandatory 24-hour blackout before polling. All major parties have confirmed they will field candidates. Independent candidates will have until June 10 to submit nomination forms with the required 100 signatures. Results are expected by midnight on election day.')],
                'dv' => ['title' => 'ތިން ދާއިރާ ބައި-އިލެކްޝަންގެ ތާރީހު ހަ ހ',
                         'slug'  => 'by-election-thareehu',
                         'excerpt' => 'ތިން ދާއިރާ ބައި-އިލެކްޝަން ޖުލައި 15 ވަނަ ދުވަހު ބާ ހ ވިއެވެ.'],
            ],
            [
                'cat' => 'health', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 6700, 'minutes_ago' => 3600, 'user' => $journalist,
                'en' => ['title' => 'WHO Commends Maldives for Achieving 95% Vaccination Coverage',
                         'slug'  => 'who-maldives-vaccination-coverage-95-percent',
                         'excerpt' => 'The World Health Organization has recognized the Maldives for achieving 95% childhood vaccination coverage, one of the highest rates in South Asia.',
                         'content' => $this->article('The World Health Organization\'s Regional Director for South-East Asia praised the Maldives\' immunization program in a formal letter to the Health Ministry this week, noting that the country has achieved 95.3% coverage for all routine childhood vaccines, placing it among the top performers in the region. Health Minister Naseem attributed the success to a combination of mobile vaccination teams reaching remote islands and a new digital tracking system that alerts parents when their child\'s vaccinations are due. The program covers 13 different vaccines from birth to age 15. The WHO highlighted that the Maldives\' zero-dose children rate — those who have received no vaccines at all — has dropped to 0.4%, compared to a regional average of 3.2%. The ministry aims to reach 97% coverage by 2027.')],
                'dv' => ['title' => 'ލިވެ ދިނުމުގެ ކަވަރޭޖް 95% ހ ހ',
                         'slug'  => 'vaccination-coverage-95',
                         'excerpt' => 'ލިވެ ދިނުމުގެ ކަވަރޭޖް 95% ހ ހ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'sports', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => true,
                'views' => 7800, 'minutes_ago' => 4320, 'user' => $editor,
                'en' => ['title' => 'Ali Rasheed Wins Bronze at Asian Athletics Championships',
                         'slug'  => 'ali-rasheed-bronze-asian-athletics',
                         'excerpt' => 'Maldivian sprinter Ali Rasheed clinched a bronze medal in the 200m event at the Asian Athletics Championships in Bangkok, the country\'s first track medal at the continental level.',
                         'content' => $this->article('Sprinter Ali Rasheed made history for Maldivian athletics on Sunday, winning a bronze medal in the 200 metres event at the Asian Athletics Championships in Bangkok\'s Supachalasai Stadium with a personal best time of 20.71 seconds. Rasheed, 23, trained at the Maldives National University athletics facility before earning a sports scholarship to train in Malaysia for two years. "I dedicate this medal to every young Maldivian who believes in their dreams," Rasheed said at the podium. The gold and silver went to Japanese and South Korean athletes respectively. Athletics Association president Mohamed Naeem called the result a "watershed moment" for track and field in the country. The government has pledged to fund Rasheed\'s training camp for the next Asian Games.')],
                'dv' => ['title' => 'ދިވެހި ދުވުންތެރިޔާ ދ. ދ. ދ ފައި ތ. ތ',
                         'slug'  => 'ali-rasheed-bronze',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'education', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 2900, 'minutes_ago' => 5040, 'user' => $admin,
                'en' => ['title' => 'University of Maldives Opens Applications for New Marine Biology Program',
                         'slug'  => 'university-maldives-marine-biology-program',
                         'excerpt' => 'The Maldives National University has launched applications for a new four-year BSc in Marine Biology developed in partnership with Australian research institutions.',
                         'content' => $this->article('The Maldives National University (MNU) has opened applications for its inaugural Bachelor of Science in Marine Biology degree program starting September 2026, developed in collaboration with James Cook University and the Australian Institute of Marine Science. The four-year program, offered at the Faculty of Sciences, will train students in coral ecology, fisheries management, oceanography, and marine conservation. Annual tuition is set at MVR 45,000, with 20 fully-funded government scholarships available for high-performing students from outer islands. Associate Professor Dr. Fathimath Ali, program coordinator, said the curriculum was designed to directly address the Maldives\' most pressing environmental challenges. Applications close June 30. The first cohort is limited to 40 students to ensure small class sizes and hands-on laboratory access.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'mnu-marine-biology',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'technology', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 3400, 'minutes_ago' => 5760, 'user' => $journalist,
                'en' => ['title' => 'Dhiraagu Expands 5G Network to 10 More Islands',
                         'slug'  => 'dhiraagu-5g-network-expansion',
                         'excerpt' => 'Maldives\' largest telecom operator has announced the expansion of its 5G network to ten additional islands in Kaafu and Alif Alif atolls, ahead of schedule.',
                         'content' => $this->article('Dhiraagu CEO Ismail Rasheed announced the ahead-of-schedule 5G expansion to 10 additional inhabited islands at a press event in Malé on Wednesday. The rollout targets Hulhumalé Phase 2, Guraidhoo, Maafushi, Thulusdhoo, Himmafushi, Dhiffushi, and four islands in Alif Alif Atoll. Average download speeds on the new 5G network have been clocked at 850 Mbps in testing conditions. The company plans to invest MVR 300 million in infrastructure upgrades over the next 24 months. Dhiraagu also unveiled its new enterprise 5G solutions for the hospitality industry, allowing resorts to offer ultra-fast connectivity to guests without traditional Wi-Fi infrastructure. The operator aims to have 5G coverage across all inhabited islands by 2029.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ 5G',
                         'slug'  => 'dhiraagu-5g',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'national', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 2100, 'minutes_ago' => 6480, 'user' => $editor,
                'en' => ['title' => 'New Bridge Connecting Hulhumalé to Gulhifalhu Opens to Traffic',
                         'slug'  => 'hulhumale-gulhifalhu-bridge-opening',
                         'excerpt' => 'The 1.4km bridge linking Hulhumalé to the industrial island of Gulhifalhu officially opened to traffic on Sunday, easing commercial freight movement in the capital region.',
                         'content' => $this->article('The 1.4 kilometre Gulhifalhu Bridge was officially opened to traffic by the Minister of Transport on Sunday morning in a ceremony attended by senior government officials and contractors. The two-lane bridge, constructed by China State Construction Engineering Corporation, took 26 months to complete at a cost of USD 85 million financed through a concessional loan from China ExIm Bank. The bridge provides a critical link between Hulhumalé Phase 2 residential areas and the Gulhifalhu Port, reducing truck travel via ferry by an estimated 40,000 journeys annually. Environmentalists have called for close monitoring of the impact on the surrounding lagoon ecosystem. The bridge is equipped with LED lighting powered by solar panels installed on the median barrier.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'gulhifalhu-bridge',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            // ─── VIDEO POSTS ─────────────────────────────────────────────────
            [
                'cat' => 'sports', 'type' => 'video', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 12000, 'minutes_ago' => 480, 'user' => $journalist,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'en' => ['title' => 'Maldives vs Bangladesh: Full Match Highlights | SAFF Championship 2026',
                         'slug'  => 'maldives-bangladesh-match-highlights-2026',
                         'excerpt' => 'Watch the full highlights of the Maldives 2-1 victory over Bangladesh in the SAFF Championship 2026 group stage.',
                         'content' => $this->article('Watch the complete highlights package from the Maldives\' historic 2-1 victory over Bangladesh in the SAFF Championship group stage in Colombo. The match featured Ibrahim Waheed\'s stunning 87th-minute header to seal the win.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ހ ހ',
                         'slug'  => 'maldives-bangladesh-highlights',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'national', 'type' => 'video', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 8500, 'minutes_ago' => 960, 'user' => $editor,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'en' => ['title' => 'President\'s Infrastructure Plan: Watch the Full Press Conference',
                         'slug'  => 'presidents-infrastructure-press-conference-video',
                         'excerpt' => 'Watch the President\'s full press conference announcing the MVR 2.5 billion infrastructure investment plan for outer atolls.',
                         'content' => $this->article('Full video of the President\'s press conference at the President\'s Office where the historic MVR 2.5 billion infrastructure investment plan for outer atolls was announced.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'president-press-conference-video',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'technology', 'type' => 'video', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 5200, 'minutes_ago' => 1440, 'user' => $admin,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'en' => ['title' => 'How the National Digital ID System Works: Explainer Video',
                         'slug'  => 'national-digital-id-explainer-video',
                         'excerpt' => 'A step-by-step explainer on how to register and use the new National Digital Identity system for accessing government services.',
                         'content' => $this->article('This explainer video walks you through the complete process of registering for the National Digital ID, verifying your identity using biometrics, and accessing government services through the gov.mv platform.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'digital-id-explainer-video',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'business', 'type' => 'video', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 3800, 'minutes_ago' => 2880, 'user' => $journalist,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'en' => ['title' => 'BML CEO Interview: Banking the Future of Maldives',
                         'slug'  => 'bml-ceo-interview-future-banking',
                         'excerpt' => 'An exclusive interview with BML CEO Karl Stumke discussing the bank\'s digital transformation, record profits, and plans to serve every Maldivian island.',
                         'content' => $this->article('In this exclusive interview, Bank of Maldives CEO Karl Stumke discusses the record-breaking financial year 2025 results, the bank\'s ambitious digital transformation program, and what the future holds for banking services in the Maldives.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'bml-ceo-interview-video',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            // ─── Additional regular posts ─────────────────────────────────
            [
                'cat' => 'international', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 1800, 'minutes_ago' => 7200, 'user' => $editor,
                'en' => ['title' => 'Sri Lanka Approves Visa-Free Travel for Maldivian Citizens',
                         'slug'  => 'sri-lanka-visa-free-maldives',
                         'excerpt' => 'Sri Lanka has announced that Maldivian nationals can now travel to the island nation visa-free for stays up to 30 days, effective June 1.',
                         'content' => $this->article('Sri Lanka\'s Ministry of Foreign Affairs announced that Maldivian nationals will be able to travel to Sri Lanka without a prior visa starting June 1, 2026, for tourist and business visits of up to 30 days. The reciprocal arrangement follows a similar visa-free facility already extended by the Maldives to Sri Lankan citizens. The Sri Lankan High Commissioner to the Maldives, Mr. Samarasinghe, said the move would strengthen people-to-people ties and boost tourism between the two nations.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'sri-lanka-visa-free',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'politics', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 2300, 'minutes_ago' => 8640, 'user' => $journalist,
                'en' => ['title' => 'MDP National Council Elects New Chairperson in Competitive Vote',
                         'slug'  => 'mdp-national-council-chairperson-elected',
                         'excerpt' => 'The Maldivian Democratic Party\'s national council has elected Ibrahim Faisal as its new chairperson, defeating two rivals in a closely contested internal election.',
                         'content' => $this->article('The Maldivian Democratic Party held its national council elections on Saturday, with Ibrahim Faisal emerging victorious as the new party chairperson after a closely contested three-way race. Faisal, a former cabinet minister and parliamentarian, secured 189 of the 342 votes cast, defeating incumbent Ali Hassan and newcomer Fathimath Hussain. Faisal pledged to focus on grassroots organisation and candidate selection for the 2027 general election. President Solih congratulated Faisal and called for party unity ahead of "the most important election in our democratic history."')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'mdp-chairperson-elected',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
            [
                'cat' => 'health', 'type' => 'article', 'featured' => false, 'breaking' => false, 'trending' => false,
                'views' => 4500, 'minutes_ago' => 10080, 'user' => $admin,
                'en' => ['title' => 'Heat Wave Warning Issued as Temperatures Reach 37°C in Male',
                         'slug'  => 'heatwave-warning-male-37-degrees',
                         'excerpt' => 'The Meteorological Service has issued a heat advisory as Malé recorded 37°C for the third consecutive day, urging residents to stay hydrated and avoid outdoor activity during peak hours.',
                         'content' => $this->article('The Maldives Meteorological Service issued a Level 2 heat advisory for the Greater Malé region and central atolls on Thursday as temperatures climbed to 37.2°C — the highest recorded in Malé in 15 years. Health authorities have urged residents, particularly the elderly and young children, to minimise outdoor exposure between 10am and 3pm. ADK Hospital reported a 30% increase in heat exhaustion cases over the past week. The meteorological office predicts the heat wave will persist for at least another five days before a weather system brings rainfall from the northwest. The government has opened six air-conditioned community centres as cooling refuges for vulnerable residents.')],
                'dv' => ['title' => 'ދ ދ ދ ދ ދ ދ ދ ދ',
                         'slug'  => 'heatwave-male-37',
                         'excerpt' => 'ދ ދ ދ ދ ދ ދ ދ ދ ދ.'],
            ],
        ];

        $createdPosts = [];
        foreach ($postsData as $pd) {
            $cat = $cats[$pd['cat']] ?? $cats->first();
            if (!$cat) continue;

            $post = Post::firstOrCreate(
                ['uuid' => Str::uuid()->toString()],
                [
                    'user_id'         => $pd['user']->id,
                    'category_id'     => $cat->id,
                    'type'            => $pd['type'],
                    'status'          => PostStatus::Published,
                    'is_featured'     => $pd['featured'],
                    'is_breaking'     => $pd['breaking'],
                    'is_trending'     => $pd['trending'],
                    'is_pinned'       => false,
                    'is_premium'      => false,
                    'published_at'    => now()->subMinutes($pd['minutes_ago']),
                    'allow_comments'  => true,
                    'allow_reactions' => true,
                    'reading_time'    => rand(3, 8),
                    'video_url'       => $pd['video_url'] ?? null,
                ]
            );

            // Update views_count (not in fillable)
            DB::table('posts')->where('id', $post->id)->update(['views_count' => $pd['views']]);

            // Translations
            foreach (['en', 'dv'] as $locale) {
                $td = $pd[$locale];
                $post->translations()->firstOrCreate(
                    ['locale' => $locale],
                    [
                        'locale'     => $locale,
                        'title'      => $td['title'],
                        'slug'       => $td['slug'],
                        'excerpt'    => $td['excerpt'],
                        'content'    => $td['content'] ?? $td['excerpt'],
                    ]
                );
            }

            $createdPosts[] = $post;
        }

        // Attach tags to posts
        $tagKeys = array_keys($tags);
        foreach ($createdPosts as $i => $post) {
            $selectedTags = array_slice($tagKeys, $i % count($tagKeys), 3);
            $tagIds = array_map(fn($k) => $tags[$k]->id, $selectedTags);
            $post->tags()->syncWithoutDetaching($tagIds);
        }

        // Update tag posts_count
        foreach ($tags as $tag) {
            DB::table('tags')->where('id', $tag->id)->update([
                'posts_count' => $tag->posts()->count() + rand(2, 15),
            ]);
        }

        $this->command->info('✅ ' . count($createdPosts) . ' posts created');

        // ── 6. Clear caches ───────────────────────────────────────────────
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        $this->command->info('✅ Cache cleared');
        $this->command->info('');
        $this->command->info('🎉 Sample data seeded successfully!');
        $this->command->info('   Admin:      admin@laranews.test / password');
        $this->command->info('   Editor:     editor@laranews.test / password');
        $this->command->info('   Journalist: journalist@laranews.test / password');
    }

    private function article(string $intro): string
    {
        return "<p>{$intro}</p>\n\n<p>Local officials have been closely monitoring the situation and coordinating with relevant government departments to ensure swift action is taken. A series of meetings and consultations are scheduled over the coming days to finalise implementation details.</p>\n\n<p>Stakeholders across various sectors have expressed cautious optimism about the developments, calling for transparent communication and inclusive decision-making processes as the situation evolves.</p>\n\n<p>Further updates are expected from official sources as more information becomes available. The public is encouraged to follow verified government channels for the latest information.</p>";
    }
}
