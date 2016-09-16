<?php
/**
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

//This file describes the module, including database tables

//Basic variables
$name = "Translation" ;
$description = "A module to provide translation management." ;
$entryURL = "translationManage.php" ;
$type = "Additional" ;
$category = "Admin" ; 
$version = "1.0.00" ; 
$author = "Craig Rayner" ; 
$url = "http://www.craigrayner.com" ;

//Module tables & gibbonSettings entries
$moduleTables = array();

//Action rows 
$actionRows[0]["name"] = "Translation" ; 
$actionRows[0]["precedence"] = "0"; 
$actionRows[0]["category"] = "Translation" ; 
$actionRows[0]["description"] = "Allows an administrator the ability to manage translation." ;
$actionRows[0]["URLList"] = "translationManage.php,translationManage_email.php,translationMerge.php" ;
$actionRows[0]["entryURL"] = "translationManage.php" ;
$actionRows[0]["defaultPermissionAdmin"] = "Y" ; 
$actionRows[0]["defaultPermissionTeacher"] = "N" ; 
$actionRows[0]["defaultPermissionStudent"] = "N" ; 
$actionRows[0]["defaultPermissionParent"] = "N" ; 
$actionRows[0]["defaultPermissionSupport"] = "N" ; 
$actionRows[0]["categoryPermissionStaff"] = "N" ; 
$actionRows[0]["categoryPermissionStudent"] = "N" ;
$actionRows[0]["categoryPermissionParent"] = "N" ; 
$actionRows[0]["categoryPermissionOther"] = "N" ; 
