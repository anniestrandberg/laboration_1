<?php
// Jag skapar en function med namn create_cvs_from_countrycode. Funktionen tar ett argument, nämligen en landskod.
function create_csv_from_country($landskod){

//Här skapar jag variablar som jag senare kommer att använda i min funktion och fylla med data.
    $pris = [];
    $status = '';
    $landskod = strtoupper($landskod);

// Detta är en kod för att göra om en CSV-fil och dess värden till en associative array, lämplig att använda med PHP.
    $array_from_csv = $fields = array(); $i = 0;
    $handle = @fopen("international-orders.csv", "r");
    if ($handle) {
        while (($row = fgetcsv($handle, 40)) !== false) {
            if (empty($fields)) {
                $fields = $row;
                continue;
            }
            foreach ($row as $k=>$value) {
                $array_from_csv[$i][$fields[$k]] = $value;
            }
            $i++;
        }
        if (!feof($handle)) {
            echo "Error";
        }
        fclose($handle);
    }

//Här är en foreachloop som jag skapat för att loopa igenom ALLA IDn i CSV-filen (den skapade arrayen).
    foreach($array_from_csv as $values){

// En if-sats som kollar om $landskod finns på någon rad i CSV-filen. Finns den pushas priserna på de raderna in i den tomma arrayen $pris.
        if(strpos($values['ID'], $landskod) == true){
            $status = 'Success';
            array_push($pris, $values['Pris']);
        }
    }   

// Om landskoden inte finns, och $status inte blir 'Success', stoppas koden och användaren meddelas på skärmen.
    if($status != 'Success'){
        exit('Gick ej att hitta');
    } else {
        print 'Landskoden finns';
// Här har jag använt mig utav array_sum (en färdig function som hjälper mig att räkna ut summan av en array).
        $total_pris = array_sum($pris);
// Här är "schemat" kan man säga, dvs i vilken orning jag vill att informationen ska visas i den nyskapde CSV-filen.
        $csv_array_output = [['Status', 'Landskod', 'Totalsumma'], [$status,  $landskod, $total_pris]];
// Här skapar jag strukturen för hur den nyskapade CSV-filen ska döpas. T.ex. SE-20180304-122344.csv
        $name_of_file = $landskod . '-' . gmdate('Ymd-his', time()) . '.csv';
// En php function som låter mig skapa den nya CSV-filen, med den information jag använt i cvs_array_output. 
// Alla de nyskapade CSV-filerna läggs i mappen 'csv_created'
            $csv_file = fopen('csv_created/'.$name_of_file, 'w');
            foreach($csv_array_output as $values){
                fputcsv($csv_file, $values);
            }
            fclose($csv_file);
    }

}

// Här kör jag funktionen och skriver vilken landskod jag vill kolla som ett argument. 
create_csv_from_country('en');