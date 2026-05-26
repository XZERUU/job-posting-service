<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeekerData
{
    public static function profile(int $userId): object
    {
        if (! Schema::hasTable('profiles')) {
            return (object) [
                'skills' => [],
                'education' => [],
                'experiences' => [],
            ];
        }

        $profile = DB::table('profiles')->where('user_id', $userId)->first();

        if (! $profile) {
            return (object) [
                'skills' => [],
                'education' => [],
                'experiences' => [],
            ];
        }

        $profile->skills = self::decodeJsonList($profile->skills ?? null);
        $profile->education = self::decodeJsonList($profile->education ?? null);
        $profile->experiences = self::decodeJsonList($profile->experiences ?? null);

        return $profile;
    }

    public static function applicationRows(int $userId, int $limit = 5)
    {
        return DB::table('applications as a')
            ->join('job_posts as jp', 'jp.id', '=', 'a.job_id')
            ->leftJoin('users as u', 'u.id', '=', 'jp.employer_id')
            ->where('a.user_id', $userId)
            ->select(
                'a.id',
                'a.status',
                'a.created_at',
                'jp.id as job_post_id',
                'jp.job_title',
                DB::raw("COALESCE(u.name, 'Unknown Company') as company_name")
            )
            ->orderByDesc('a.created_at')
            ->limit($limit)
            ->get();
    }

    public static function stats(int $userId): array
    {
        $base = DB::table('applications')->where('user_id', $userId);

        return [
            'applied' => (clone $base)->count(),
            'under_review' => (clone $base)
                ->whereIn('status', ['pending', 'reviewing', 'under_review', 'interview'])
                ->count(),
            'hired' => (clone $base)
                ->whereIn('status', ['approved', 'hired'])
                ->count(),
        ];
    }

    public static function recommendedJobs(int $userId, int $limit = 4)
    {
        return DB::table('job_posts as jp')
            ->leftJoin('users as u', 'u.id', '=', 'jp.employer_id')
            ->where('jp.status', 'active')
            ->whereNotExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('applications as a')
                    ->whereColumn('a.job_id', 'jp.id')
                    ->where('a.user_id', $userId);
            })
            ->select(
                'jp.id',
                'jp.job_title',
                'jp.location',
                DB::raw("COALESCE(u.name, 'Unknown Company') as company_name")
            )
            ->orderByDesc('jp.posted_at')
            ->limit($limit)
            ->get();
    }

    public static function profileStrength(object $user, object $profile): array
    {
        $checklist = [
            'basic_information' => ! empty($user->name) && ! empty($user->email),
            'headline' => ! empty($profile->headline),
            'phone' => ! empty($profile->phone),
            'resume' => ! empty($profile->resume_path),
        ];

        $completed = collect($checklist)->filter()->count();

        return [
            'percent' => (int) round(($completed / count($checklist)) * 100),
            'checklist' => $checklist,
        ];
    }

    private static function decodeJsonList($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value);

        return is_array($decoded) ? $decoded : [];
    }
}
