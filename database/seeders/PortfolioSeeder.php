<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update the single portfolio profile
        User::updateOrCreate(
            ['email' => 'Awsffcs@gmail.com'],
            [
                'name' => 'أوس فواز أوس بن الفوزان',
                'phone' => '+966555239428',
                'job_title' => 'خبير تقني سعودي متخصص في الأمن السيبراني والذكاء الاصطناعي',
                'professional_summary' => 'خبير تقني سعودي متخصص في الأمن السيبراني والذكاء الاصطناعي، أمتلك شغفاً عميقاً بالتقنية ورؤية واضحة لتسخيرها في حماية الخصوصية وتعزيز منظومات الأمن الرقمي. أتمتع بخلفية تقنية قوية مدعومة بشهادات احترافية متعددة، إلى جانب خبرات تدريبية وإعلامية مميزة تحققت في سن مبكرة. أسعى لتقديم حلول مبتكرة ذات أثر مستدام، وأطمح إلى تمثيل المملكة العربية السعودية في المحافل التقنية العالمية، والمساهمة الفاعلة في تحقيق مستهدفات رؤية 2030 السعودية.',
                'password' => Hash::make('password'),
                'social_links' => [
                    'email' => 'Awsffcs@gmail.com',
                ],
            ]
        );
    }
}
