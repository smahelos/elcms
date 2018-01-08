<?php
/**
 * Created by PhpStorm.
 * User: Lukáš
 * Date: 30. 10. 2017
 * Time: 17:03
 */

namespace App\Helpers;

use \Nette\Object,
    \Nette\Utils\DateTime;

/**
 * Class Filters
 * @package App\Helpers
 */
class Filters extends Object
{
    /**
     * @param $dateTimeObject
     * @param $format
     * @param string $lang
     * @param bool $decline
     *
     * @return mixed|DateTime|string
     */
    public static function customDate($dateTimeObject, $format, $lang = 'cs', $decline = false)
    {
        $date = new DateTime($dateTimeObject);
        $numberOfDay = $date->format('N');
        $numberOfMonth = $date->format('n');
        $englishDays = array('1' => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $englishMonths = array('1' => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

        if ($lang === 'cs') {
            $daysNames = array('1' => 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota', 'Neděle');
            if ($decline === true) {
                $monthsNames = array('1' => 'Ledna', 'Února', 'Března', 'Dubna', 'Května', 'Června', 'Července', 'Srpna', 'Září', 'Října', 'Listopadu', 'Prosince');
            } else {
                $monthsNames = array('1' => 'Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec');
            }
        } else {
            $daysNames = array('1' => 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
            $monthsNames = array('1' => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        }

        $englishDay = $englishDays[$numberOfDay];
        $translatedDay = $daysNames[$numberOfDay];

        $englishMonth = $englishMonths[$numberOfMonth];
        $translatedMonth = $monthsNames[$numberOfMonth];

        $formatedDate = $date->format($format);

        if (strpos($formatedDate, $englishMonth)) {
            $date = str_replace(
                [$englishDay, $englishMonth],
                [$translatedDay, $translatedMonth],
                $formatedDate
            );

            return $date;
        }

        return $formatedDate;
    }
}