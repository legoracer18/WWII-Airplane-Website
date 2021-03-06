<?php

/*
 * DOCUMENT     : /model/model.php
 * AUTHOR       : James Park
 * SITE         : www.worldwartwoairplanes.com
 * COPYRIGHT    : 2014
 * DESCRIPTION  : This is the main model file for the site 
 *                www.worldwartwoairplanes.com
 */

/*
 * This grabs the functions.php file that will grab the connection file
 * that will have the PDO connection that will be used.
 */
require $_SERVER['DOCUMENT_ROOT'] . '/lib/functions.php';

/*
 * This gets the information for the support pages like Style Guide, etc.
 */
function getSupport($id) {
   $conn = dbConnection();
   $sql = 'SELECT title, html_code '
         .'FROM support '
         .'WHERE support_ID = :id';
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $results = $stmt->fetch();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This will get the main pictures for the planes that are from allied or
 * axis countries as well as their plane_id and type so that they can be 
 * ordered by those values.
 */
function getCountryCat($c1, $c2, $c3) {
   $conn = dbConnection();
   $sql = "SELECT a.country_ID, a.plane_ID, i.urlPATH, i.alt, a.plane_type, c.image_url, a.plane_name, c.flagAlt "
         ."FROM airplanes a INNER JOIN images i "
         ."ON a.plane_ID = i.plane_ID "
         ."INNER JOIN country c "
         ."ON a.country_ID = c.country_ID "
         ."WHERE (a.country_ID = :c1 "
         ."OR a.country_ID = :c2 "
         ."OR a.country_ID = :c3) "
         ."AND i.main = 'Y' "
         ."ORDER BY a.country_ID, plane_type, a.plane_ID";
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':c1', $c1, PDO::PARAM_INT);
      $stmt->bindValue(':c2', $c2, PDO::PARAM_INT);
      $stmt->bindValue(':c3', $c3, PDO::PARAM_INT);
      $stmt->execute();
      $results = $stmt->fetchAll();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This will get the main pictures for the planes that are fighters, bombers
 * and transports, as well as their plane_id and type so that they can be 
 * ordered by those values.
 */
function getTypeCat($type) {
   $conn = dbConnection();
   $sql = "SELECT a.country_ID, a.plane_ID, i.urlPATH, i.alt, a.plane_type, c.image_url, a.plane_name, c.flagAlt "
         ."FROM airplanes a INNER JOIN images i "
         ."ON a.plane_ID = i.plane_ID "
         ."INNER JOIN country c "
         ."ON a.country_ID = c.country_ID "
         ."WHERE a.plane_type = :type "
         ."AND i.main = 'Y' "
         ."ORDER BY a.country_ID, a.plane_ID";
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':type', $type, PDO::PARAM_STR);
      $stmt->execute();
      $results = $stmt->fetchAll();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This will get the plane info plus the main picture for the plane.
 */
function getPlaneInfo($id) {
   $conn = dbConnection();
   $sql = "SELECT a.plane_name, a.plane_type, a.description, a.sources, i.urlPATH, i.alt "
         ."FROM airplanes a INNER JOIN images i "
         ."ON a.plane_ID = i.plane_ID "
         ."WHERE a.plane_ID = :id "
         ."AND i.main = 'Y'";

   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $results = $stmt->fetch();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This will get the specs of a given plane, plus the counrty flag.
 */
function getSpecsInfo($id) {
   $conn = dbConnection();
   $sql = "SELECT c.image_url, c.flagAlt, s.maker, s.first_flight, s.number_built, "
         ."s.crew, s.length, s.wingspan, s.height, s.max_speed, s.cruise_speed, "
         ."s.stall_speed, s.distance, s.guns, s.bombs, a.plane_name "
         ."FROM airplanes a INNER JOIN specification s "
         ."ON a.plane_ID = s.plane_ID "
         ."INNER JOIN country c "
         ."ON a.country_ID = c.country_ID "
         ."WHERE s.plane_ID = :id";
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $results = $stmt->fetch();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This gets the similar planes based on the type of the plane being displayed.
 */
function getSimilarPlanes($type) {
   $conn = dbConnection();
   $sql = "SELECT a.plane_ID, a.plane_name, c.image_url, c.flagAlt "
         ."FROM airplanes a INNER JOIN country c "
         ."ON a.country_ID = c.country_ID "
         ."WHERE a.plane_type = :type "
         ."ORDER BY a.country_ID, a.plane_ID";
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':type', $type, PDO::PARAM_STR);
      $stmt->execute();
      $results = $stmt->fetchAll();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}

/*
 * This gets the pictures of the plane based on the plane_id so that
 * it can be put into the slider.
 */
function getPlanePics($id) {
   $conn = dbConnection();
   $sql = "SELECT urlPATH, alt FROM images WHERE plane_ID = :id";
   try {
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $results = $stmt->fetchAll();
      $stmt->closeCursor();
   } catch (PDOException $e) {
      header('Location: /500.php');
      exit;
   }

   if (!empty($results)) {
      return $results;
   } else {
      header('Location: /500.php');
      exit;
   }
}
?>