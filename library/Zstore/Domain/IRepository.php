<?php
namespace Zstore\Domain;

interface IRepository
{
    public function saveData($info, $row);
    public function remove($id);
}
