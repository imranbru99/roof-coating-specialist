<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\KnowledgeArticle;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Product;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'manager', 'staff'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '(512) 555-0100',
            ]
        );
        $admin->syncRoles(['admin']);

        User::query()->updateOrCreate(
            ['email' => 'manager@solarashieldcoatings.com'],
            [
                'name' => 'Diana Morales',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'phone' => '(512) 555-0101',
            ]
        )->syncRoles(['manager']);

        User::query()->updateOrCreate(
            ['email' => 'crew@solarashieldcoatings.com'],
            [
                'name' => 'Jake Whitfield',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '(512) 555-0102',
            ]
        )->syncRoles(['staff']);

        $settings = [
            'company_name' => 'SolaraShield Coatings',
            'company_phone' => '(512) 555-0148',
            'company_email' => 'hello@solarashieldcoatings.com',
            'whatsapp' => '15125550148',
            'service_area' => 'Austin, Round Rock, Cedar Park & Central Texas',
            'tax_rate' => '8.25',
        ];
        foreach ($settings as $key => $value) {
            Setting::setValue($key, $value);
        }

        $products = [
            ['name' => 'Acrylic Elastomeric White (5-gal)', 'sku' => 'ACR-WHT-5', 'category' => 'coating', 'unit_cost' => 68, 'unit_price' => 118, 'stock_qty' => 48],
            ['name' => 'High-Solids Silicone (5-gal)', 'sku' => 'SIL-HS-5', 'category' => 'coating', 'unit_cost' => 145, 'unit_price' => 245, 'stock_qty' => 32],
            ['name' => 'Metal Roof Primer (5-gal)', 'sku' => 'PRM-MTL-5', 'category' => 'primer', 'unit_cost' => 55, 'unit_price' => 95, 'stock_qty' => 28],
            ['name' => 'Seam Fabric Roll 6"', 'sku' => 'FAB-6-100', 'category' => 'reinforcement', 'unit_cost' => 22, 'unit_price' => 42, 'stock_qty' => 60],
            ['name' => 'Flashing Mastic Cartridge', 'sku' => 'MAS-FLS-1', 'category' => 'reinforcement', 'unit_cost' => 8, 'unit_price' => 16, 'stock_qty' => 120],
            ['name' => 'Drain Ring & Basket Kit', 'sku' => 'DRN-KIT-1', 'category' => 'accessory', 'unit_cost' => 34, 'unit_price' => 68, 'stock_qty' => 24],
            ['name' => 'Spray Tip Set (roof coating)', 'sku' => 'TIP-RC-SET', 'category' => 'tool', 'unit_cost' => 18, 'unit_price' => 36, 'stock_qty' => 40],
            ['name' => 'Cool-Roof Topcoat Additive', 'sku' => 'ADD-CR-1', 'category' => 'coating', 'unit_cost' => 28, 'unit_price' => 52, 'stock_qty' => 35],
        ];
        foreach ($products as $product) {
            Product::query()->updateOrCreate(['sku' => $product['sku']], $product + ['unit' => 'ea', 'is_active' => true]);
        }

        $customers = collect([
            ['name' => 'Rachel Nguyen', 'email' => 'rachel@example.com', 'phone' => '(512) 555-3301', 'company' => null, 'city' => 'Austin', 'state' => 'TX', 'zip' => '78745', 'address' => '4120 S Lamar Blvd'],
            ['name' => 'Omar Haddad', 'email' => 'omar@hillcountryretail.com', 'phone' => '(512) 555-3310', 'company' => 'Hill Country Retail LLC', 'city' => 'Austin', 'state' => 'TX', 'zip' => '78759', 'address' => '9800 Research Blvd'],
            ['name' => 'Megan Torres', 'email' => 'megan@example.com', 'phone' => '(512) 555-3399', 'company' => null, 'city' => 'Round Rock', 'state' => 'TX', 'zip' => '78664', 'address' => '220 Palm Valley Blvd'],
            ['name' => 'Chris Ellison', 'email' => 'chris.ellison@example.com', 'phone' => '(512) 555-3288', 'company' => null, 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'address' => '701 Cypress Creek Rd'],
            ['name' => 'Priya Kapoor', 'email' => 'priya@lakelineware.com', 'phone' => '(512) 555-3277', 'company' => 'Lakeline Warehouse Co', 'city' => 'Cedar Park', 'state' => 'TX', 'zip' => '78613', 'address' => '1400 Industrial Blvd'],
        ])->map(fn ($c) => Customer::query()->updateOrCreate(['email' => $c['email']], $c));

        $projects = [
            ['customer' => 0, 'title' => 'Residential cool-roof acrylic — 2,400 sf', 'service_type' => 'elastomeric', 'status' => 'scheduled', 'sq_ft' => 2400, 'quoted_amount' => 9800],
            ['customer' => 1, 'title' => 'Retail center silicone restoration', 'service_type' => 'silicone', 'status' => 'in_progress', 'sq_ft' => 18500, 'quoted_amount' => 92000],
            ['customer' => 2, 'title' => 'Metal panel coating + fastener pass', 'service_type' => 'metal', 'status' => 'quoted', 'sq_ft' => 4200, 'quoted_amount' => 16800],
            ['customer' => 3, 'title' => 'Leak repair & seam fabric package', 'service_type' => 'leak_repair', 'status' => 'completed', 'sq_ft' => 800, 'quoted_amount' => 4200, 'final_amount' => 4450],
            ['customer' => 4, 'title' => 'Warehouse acrylic coat — Phase A', 'service_type' => 'commercial', 'status' => 'invoiced', 'sq_ft' => 32000, 'quoted_amount' => 118000, 'final_amount' => 116500],
        ];

        $projectModels = [];
        foreach ($projects as $i => $p) {
            $customer = $customers[$p['customer']];
            $projectModels[] = Project::query()->updateOrCreate(
                ['job_number' => 'JOB-26'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $customer->id,
                    'title' => $p['title'],
                    'service_type' => $p['service_type'],
                    'status' => $p['status'],
                    'sq_ft' => $p['sq_ft'],
                    'quoted_amount' => $p['quoted_amount'],
                    'final_amount' => $p['final_amount'] ?? null,
                    'start_date' => now()->subDays(10 - $i)->toDateString(),
                    'site_address' => trim(($customer->address ?? '').', '.$customer->city.', '.$customer->state),
                    'notes' => 'Seeded demo job for SolaraShield ERP.',
                ]
            );
        }

        foreach ([0, 1, 3, 4] as $idx) {
            $project = $projectModels[$idx];
            $invoice = Invoice::query()->updateOrCreate(
                ['invoice_number' => 'INV-26'.str_pad((string) ($idx + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'customer_id' => $project->customer_id,
                    'project_id' => $project->id,
                    'issue_date' => now()->subDays(8 - $idx)->toDateString(),
                    'due_date' => now()->addDays(14)->toDateString(),
                    'subtotal' => $project->final_amount ?? $project->quoted_amount,
                    'tax' => round(($project->final_amount ?? $project->quoted_amount) * 0.0825, 2),
                    'total' => round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2),
                    'amount_paid' => in_array($project->status, ['completed', 'invoiced'], true) ? round(($project->final_amount ?? $project->quoted_amount) * 1.0825, 2) : 0,
                    'status' => in_array($project->status, ['completed', 'invoiced'], true) ? 'paid' : 'sent',
                ]
            );

            if ($invoice->status === 'paid') {
                Payment::query()->updateOrCreate(
                    ['reference' => 'PMT-'.$invoice->invoice_number],
                    [
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total,
                        'paid_at' => now()->subDays(3)->toDateString(),
                        'method' => 'card',
                        'notes' => 'Demo payment',
                    ]
                );
            }
        }

        Appointment::query()->updateOrCreate(
            ['title' => 'Roof survey — Nguyen'],
            [
                'customer_id' => $customers[0]->id,
                'project_id' => $projectModels[0]->id,
                'type' => 'survey',
                'starts_at' => now()->addDays(2)->setTime(9, 0),
                'ends_at' => now()->addDays(2)->setTime(10, 0),
                'status' => 'scheduled',
                'location' => $customers[0]->address,
            ]
        );
        Appointment::query()->updateOrCreate(
            ['title' => 'Retail silicone spray day 1'],
            [
                'customer_id' => $customers[1]->id,
                'project_id' => $projectModels[1]->id,
                'type' => 'install',
                'starts_at' => now()->addDays(9)->setTime(6, 30),
                'ends_at' => now()->addDays(9)->setTime(15, 30),
                'status' => 'confirmed',
                'location' => $customers[1]->address,
            ]
        );

        $posts = [
            [
                'title' => 'Cool Roof vs Tear-Off: When Coating Wins in Austin',
                'excerpt' => 'Not every aging flat roof needs a dumpster. Here is when SolaraShield recommends reflective coating instead of tear-off.',
                'cover' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=80',
                'body' => "Austin summers punish dark membranes. If insulation is dry, seams can be reinforced, and the deck drains reasonably well, a reflective elastomeric or silicone system can restore waterproofing and cut heat gain without the cost and disruption of tear-off.\n\nSolaraShield surveys for wet insulation, soft decks, and chronic ponding before recommending coating. When cores show saturated board or structural issues, we say so — coating is not a miracle paint.",
            ],
            [
                'title' => 'Silicone vs Acrylic: Matching Chemistry to Ponding',
                'excerpt' => 'Acrylic loves dry slopes. Silicone thrives where water sits. Your roof geometry decides.',
                'cover' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1400&q=80',
                'body' => "Acrylic elastomeric coatings deliver excellent reflectivity and value on well-drained decks. High-solids silicone handles ponding water that acrylic systems may not survive long-term.\n\nSolaraShield maps ponding zones during the survey and specifies chemistry accordingly — often acrylic on slopes with silicone at drains and low spots. Ask for that map with your proposal.",
            ],
            [
                'title' => 'Mil Thickness: The Spec That Protects Your Warranty',
                'excerpt' => 'Pretty white roofs fail when crews chase coverage instead of dry film thickness.',
                'cover' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1400&q=80',
                'body' => "Manufacturer warranties require documented wet and dry mils. We spray or roll to published rates, check with gauges, and photograph coverage before demobilizing.\n\nSkipping mil checks is the most expensive shortcut in roof coating. Demand thickness documentation with your closeout package.",
            ],
        ];
        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'cover_image' => $post['cover'],
                    'is_published' => true,
                    'published_at' => now()->subDays(random_int(2, 20)),
                ]
            );
        }

        $articles = [
            ['category' => 'issues', 'title' => 'Blisters appearing after coating', 'body' => "1. Confirm substrate was dry — trapped moisture causes blisters.\n2. Check if primer was skipped on porous or oxidized surfaces.\n3. Document blister locations with photos.\n4. Call SolaraShield — do not puncture randomly; we may need targeted cut-and-patch before recoat."],
            ['category' => 'issues', 'title' => 'Ponding water still on newly coated roof', 'body' => "Coating does not fix structural low spots. Verify drains are clear and scuppers open. Chronic 48-hour ponding may need tapered insulation, additional drains, or silicone-rated systems. Schedule a follow-up survey."],
            ['category' => 'guides', 'title' => 'Roof coating project timeline', 'body' => "Day 0: Survey + moisture assessment.\nDay 1–2: Proposal, material order, weather window.\nDay 3: Prep, clean, seam reinforcement.\nDay 4–5: Primer and base/finish coats to mil spec.\nDay 6: Punch list, mil report, warranty packet.\nRain delays add buffer — coatings need dry decks."],
            ['category' => 'guides', 'title' => 'How to prepare for a coating crew', 'body' => "Clear roof access and parking for drum delivery. Notify tenants of noise/odor windows. Secure HVAC tech for unit curb access if needed. Keep drains free of debris the week before. Provide roof hatch keys on day one."],
            ['category' => 'admin', 'title' => 'Admin ERP quick start', 'body' => "Login at /admin with admin@example.com / password.\n1. Add customers in CRM.\n2. Create a Job/Project with square footage and quote.\n3. Schedule appointments for surveys/installs.\n4. Convert completed jobs to invoices and record payments.\n5. Use AI Studio for blog drafts and troubleshooting suggestions.\n6. Update company Settings for phone, WhatsApp, and tax rate."],
            ['category' => 'admin', 'title' => 'Roles: Admin, Manager, Staff', 'body' => "Admin: full ERP + settings + users.\nManager: jobs, inventory, invoices, scheduling.\nStaff: field-facing records and appointment updates.\nAssign roles on the Users resource."],
            ['category' => 'troubleshoot', 'title' => 'Job delayed by unexpected wet insulation', 'body' => "Stop coating. Core suspect areas, document wet board, and revise scope for replacement or drying. Update the job notes, adjust appointments, and send a change order before remobilizing."],
            ['category' => 'troubleshoot', 'title' => 'Chatbot giving demo answers only', 'body' => "Demo mode is expected without API keys. Add OPENAI_API_KEY or GEMINI_API_KEY to .env, then clear config cache. The public chatbot and AI Studio will prefer live models when keys are present."],
        ];
        foreach ($articles as $article) {
            KnowledgeArticle::query()->updateOrCreate(
                ['slug' => Str::slug($article['title'])],
                [
                    'title' => $article['title'],
                    'category' => $article['category'],
                    'body' => $article['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
