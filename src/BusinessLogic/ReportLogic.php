<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Report;
use Romi\Domain\Guest;
use Romi\Domain\GuestsInRoom;
use Romi\Domain\Room;
use Romi\Domain\BookingRoom;
use Romi\Domain\Booking;

class ReportLogic extends BaseLogic
{


	public function loadInfoGuestOverNight($timeDay)
	{
	$dql = "SELECT  r.name as nameRoom, g.name as nameGuest,  g.idCardNo , g.address , br.dayCheckin , br.dayCheckout as dayCheckout 
	  FROM Romi\Domain\BookingRoom br 
	  LEFT JOIN Romi\Domain\Booking b WITH b.id = br.bookingId 
	  LEFT JOIN Romi\Domain\GuestsInRoom gr WITH gr.bookingRoomId = br.id 
	  LEFT JOIN Romi\Domain\Guest g WITH g.id = gr.guestId 
	  LEFT JOIN Romi\Domain\Room r WITH r.id = br.roomId 
		WHERE  (br.dayCheckin <= :timeDay AND br.dayCheckout >= :timeDay )";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameter('timeDay', $timeDay);
		return $query->getResult();
	}


	public function reportByMonth($startTime, $endTime)
	{

		$startMonth = $startTime->format("m");
		$startYear = $startTime->format("Y");
		$endMonth = $endTime->format("m");
		$endYear = $endTime->format("Y");

		$dql = "SELECT report.month, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.month >= :startMonth AND report.year = :startYear  GROUP BY report.month, report.year";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameters(array(
			'startYear' => $startYear,
			'startMonth' => $startMonth,
		));

		$result = array();

		if (!empty($query->getResult())) {
			array_push($result, $query->getResult());
		}

		if ($endYear > $startYear) {
			$yearInterval = $endYear - $startYear;
			$year = $startYear + 1;

			for ($i = 1; $i <= $yearInterval; $i++) {
				if ($year == $endYear) {
					$month = $endMonth;
					$dql = "SELECT report.month, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.month <= :startMonth AND report.year = :startYear  GROUP BY report.month, report.year";
				} else {
					$month = 0;
					$dql = "SELECT report.month, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.month >= :startMonth AND report.year = :startYear  GROUP BY report.month, report.year";
				}
				$query = $this->getEntityManager()->createQuery($dql);
				$query->setParameters(array(
					'startYear' => $year,
					'startMonth' => $month,
				));
				if (!empty($query->getResult())) {
					array_push($result, $query->getResult());
				}
				$year++;
			}
		}
		return $result;
	}

	public function reportByQuarter($startTime, $endTime)
	{

		$startQuarter = $this->getQuarterFromDate($startTime);
		$startYear = $startTime->format("Y");
		$endQuarter = $this->getQuarterFromDate($endTime);
		$endYear = $endTime->format("Y");

		$dql = "SELECT report.quarter, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.quarter >= :startQuarter AND report.year = :startYear  GROUP BY report.quarter, report.year";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameters(array(
			'startYear' => $startYear,
			'startQuarter' => $startQuarter,
		));

		$result = array();

		if (!empty($query->getResult())) {
			array_push($result, $query->getResult());
		}

		if ($endYear > $startYear) {
			$yearInterval = $endYear - $startYear;
			$year = $startYear + 1;

			for ($i = 1; $i <= $yearInterval; $i++) {
				if ($year == $endYear) {
					$quarter = $endQuarter;
					$dql = "SELECT report.quarter, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.quarter <= :startQuarter AND report.year = :startYear  GROUP BY report.quarter, report.year";
				} else {
					$quarter = 0;
					$dql = "SELECT report.quarter, report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.quarter >= :startQuarter AND report.year = :startYear  GROUP BY report.quarter, report.year";
				}
				$query = $this->getEntityManager()->createQuery($dql);
				$query->setParameters(array(
					'startYear' => $year,
					'startQuarter' => $quarter,
				));
				if (!empty($query->getResult())) {
					array_push($result, $query->getResult());
				}
				$year++;
			}
		}
		return $result;
	}

	public function reportByYear($startTime, $endTime)
	{

		$startYear = $startTime->format("Y");
		$endYear = $endTime->format("Y");

		$dql = "SELECT report.year, SUM(report.amount) as income FROM Romi\Domain\PaymentRef report WHERE report.year >= :startYear AND report.year <= :endYear  GROUP BY report.year";
		$query = $this->getEntityManager()->createQuery($dql);
		$query->setParameters(array(
			'startYear' => $startYear,
			'endYear' => $endYear
		));
		return $query->getResult();
	}

	public function getQuarterFromDate($date)
	{
		$month = $date->format('m');
		switch ($month) {
			case 1:
			case 2:
			case 3: {
					return 1;
				}
			case 4:
			case 5:
			case 6: {
					return 2;
				}
			case 7:
			case 8:
			case 9: {
					return 3;
				}
			case 10:
			case 11:
			case 12: {
					return 4;
				}
			default: {
					return 0;
				}
		}
	}
}
