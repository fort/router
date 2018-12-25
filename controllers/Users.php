<?php

/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 12/25/18
 * Time: 4:02 PM
 */
class Users
{
    protected $users = array();


    public function __construct()
    {
        $this->users = [
            ['id' => '1', 'name' => 'CoolUser', 'status' => 'locked', 'role' => 'user'],
            ['id' => '2', 'name' => 'MixUser', 'status' => 'active', 'role' => 'admin'],
        ];

    }

    public function getList()
    {
        return $this->users;
    }

    public function getFilteredUsers($role, $status = false)
    {
        return array_filter($this->users, function ($item) use($status, $role) {
            if ($item['role'] !== $role) return false;
            return $status ?  $item['status'] == $status : true;
        });
    }

    public function getPostComment(Request $request, $postId, $commentId)
    {
        var_dump($request->getUri(), $postId, $commentId);
    }
}