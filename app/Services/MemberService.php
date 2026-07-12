<?php

namespace App\Services;

use App\Models\Member;
use Illuminate\Support\Facades\Storage;

class MemberService
{
    public function create(array $data): Member
    {
        if (isset($data['photo'])) {
            $data['photo'] = $data['photo']
                ->store('members', 'public');
        }

        return Member::create($data);
    }

    public function update(Member $member, array $data): Member
    {
        if (isset($data['photo'])) {

            if ($member->photo) {
                Storage::disk('public')
                    ->delete($member->photo);
            }

            $data['photo'] = $data['photo']
                ->store('members', 'public');
        }

        $member->update($data);

        return $member;
    }

    public function delete(Member $member): void
    {
        if ($member->photo) {
            Storage::disk('public')
                ->delete($member->photo);
        }

        $member->delete();
    }
}