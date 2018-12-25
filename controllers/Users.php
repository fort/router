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

    public function getFilteredUsers($status, $role = false)
    {
        return array_filter($this->users, function ($item) use($status, $role) {
            return $item['status'] == $status && $item['role'] == $role;
        });
    }
}