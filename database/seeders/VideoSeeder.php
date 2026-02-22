<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            [
                'title'        => 'مقدمة في الأمن السيبراني',
                'description'  => 'فيديو تعريفي يشرح أساسيات الأمن السيبراني وأهميته في حماية المؤسسات والأفراد من التهديدات الإلكترونية.',
                'video_url'    => 'https://www.youtube.com/watch?v=inWWhr5tnEA',
                'is_published' => true,
                'views'        => 1250,
            ],
            [
                'title'        => 'كيف تحمي نفسك من الاختراق',
                'description'  => 'نصائح عملية ومهمة لحماية أجهزتك وحساباتك الشخصية من محاولات الاختراق والتصيد الإلكتروني.',
                'video_url'    => 'https://www.youtube.com/watch?v=hXSFdwIOfnE',
                'is_published' => true,
                'views'        => 980,
            ],
            [
                'title'        => 'التحول الرقمي في السعودية - رؤية 2030',
                'description'  => 'نظرة شاملة على مبادرات التحول الرقمي في المملكة العربية السعودية وأثرها على القطاعين الحكومي والخاص.',
                'video_url'    => 'https://www.youtube.com/watch?v=SqcY0GlETPk',
                'is_published' => true,
                'views'        => 756,
            ],
            [
                'title'        => 'أساسيات التشفير وحماية البيانات',
                'description'  => 'شرح مبسط لمفاهيم التشفير وكيفية استخدامه في حماية البيانات والاتصالات الإلكترونية.',
                'video_url'    => 'https://www.youtube.com/watch?v=jhXCTbFnK8o',
                'is_published' => true,
                'views'        => 543,
            ],
            [
                'title'        => 'محاضرة: التوعية بمخاطر الهندسة الاجتماعية',
                'description'  => 'محاضرة توعوية حول أساليب الهندسة الاجتماعية وكيفية التعرف عليها وتجنب الوقوع ضحية لها.',
                'video_url'    => 'https://www.youtube.com/watch?v=lJ4jFTSiRfM',
                'is_published' => true,
                'views'        => 412,
            ],
            [
                'title'        => 'ورشة عمل: أدوات اختبار الاختراق',
                'description'  => 'ورشة عمل عملية حول أهم أدوات اختبار الاختراق المستخدمة في تقييم أمن الشبكات والأنظمة.',
                'video_url'    => 'https://www.youtube.com/watch?v=3Kq1MIfTWCE',
                'is_published' => false,
                'views'        => 128,
            ],
        ];

        foreach ($videos as $data) {
            Video::create($data);
        }
    }
}
