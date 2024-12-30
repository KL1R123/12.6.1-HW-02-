<?php


$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


function getFullnameFromParts(string $surname, string $name, string $patronomyc): string
{
    return $surname . " " . $name . " " . $patronomyc;
}


function getPartsFromFullname(string $fullname): array
{
    $parts = explode(" ", $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronomyc' => $parts[2]
    ];
}


function getShortName(string $fullname): string
{
    $parts = getPartsFromFullname($fullname);
    return $parts['name'] . " " . mb_substr($parts['surname'], 0, 1) . ".";
}




function getGenderFromName(string $fullname): int
{
    $parts = getPartsFromFullname($fullname);
    $gender = 0;
    //анализ фамилии
    if(mb_substr($parts['surname'], -2) == 'ва')
      $gender -=1;
    elseif(mb_substr($parts['surname'], -1) == 'в')
      $gender +=1;

      //анализ имени
    if(mb_substr($parts['name'], -1) == 'а' || mb_substr($parts['name'], -1) == 'я')
      $gender -=1;
    elseif(mb_substr($parts['name'], -1) == 'й' || mb_substr($parts['name'], -1) == 'н' )
      $gender +=1;

      //анализ отчества
   if(mb_substr($parts['patronomyc'], -3) == 'вна')
      $gender -=1;
   elseif(mb_substr($parts['patronomyc'], -2) == 'ич')
    $gender +=1;

    return $gender <=> 0;
}


function getGenderDescription(array $persons_array): string
{
    $menCount = 0;
    $womenCount = 0;
    $undefinedCount = 0;

    foreach($persons_array as $person){
      $gender = getGenderFromName($person['fullname']);
      if($gender > 0)
        $menCount++;
      elseif($gender < 0)
        $womenCount++;
      else
      $undefinedCount++;
    }

    $totalCount = count($persons_array);
    $menPercent = round(($menCount / $totalCount) * 100, 1);
    $womenPercent = round(($womenCount / $totalCount) * 100, 1);
    $undefinedPercent = round(($undefinedCount / $totalCount) * 100, 1);

    return "Гендерный состав аудитории:\n---------------------------\nМужчины - {$menPercent}%\nЖенщины - {$womenPercent}%\nНе удалось определить - {$undefinedPercent}%";

}


 
 
function getPerfectPartner(string $surname, string $name, string $patronomyc, array $persons_array): string
{
    $surname = mb_convert_case($surname, MB_CASE_TITLE);
    $name = mb_convert_case($name, MB_CASE_TITLE);
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE);

    $fullname = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullname);

    while (true) {
      $randomIndex = array_rand($persons_array);
      $randomPerson = $persons_array[$randomIndex];

      if(getGenderFromName($randomPerson['fullname']) != $gender)
      {
         $percent = round(rand(5000, 10000) / 100, 2);
        return getShortName($fullname) . " + " . getShortName($randomPerson['fullname']) . " = \n♡ Идеально на " . $percent . "% ♡";
      }
    }
}




echo "<h3>Пример использования getGenderDescription:</h3>";
echo getGenderDescription($example_persons_array) . "<br><br>";

echo "<h3>Пример использования getPerfectPartner:</h3>";
echo getPerfectPartner("иванов", "иван", "иванович", $example_persons_array) . "<br>";
echo getPerfectPartner("СТЕПАНОВА", "Наталья", "Степановна", $example_persons_array) . "<br>";
?>