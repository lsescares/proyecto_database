<!DOCTYPE html>
<html>
    <head>
        <title>Consulta3</title>
    </head>
<body>
    <h3><a href="http://grupo03.cc3201.dcc.uchile.cl/inicio.html">pagina inicial</a></h3>
    <h1>Consulta 3:</h1>

    <?php
        echo "<table>";
        echo "<tr>
                <th>Ciudad</th>
                <th>Nombre del Equipo</th>
                <th>Cantidad de veces que aparece</th>
              </tr>";

        class TableRows extends RecursiveIteratorIterator {
            function __construct($it) {
                parent::__construct($it, self::LEAVES_ONLY);
            }
            function current() {
                return "<td>" . parent::current(). "</td>";
            }
            function beginChildren() {
                echo "<tr>";
            }
            function endChildren() {
                echo "</tr>" . "\n";
            }
        }
        try {
           $pdo = new PDO('pgsql:
                           host=localhost;
                           port=5432;
                           dbname=cc3201;
                           user=cc3201;
                           password=arribade4');
           $variable3=$_GET['input3'];
           $stmt = $pdo->prepare('SELECT shortname, teamname, COUNT(seasonofgame) AS cantidad
                                    FROM(
                                    SELECT DISTINCT PP.seasonofgame, shortname, teamname
                                    FROM relacion_powerplays PP, (
                                    SELECT seasonofgame, MAX(relacion)
                                    FROM relacion_powerplays
                                    GROUP BY seasonofgame) M
                                    WHERE M.max = PP.relacion
                                    AND M.seasonofgame = PP.seasonofgame) R
                                    GROUP BY shortname, teamname
                                    ORDER BY cantidad DESC
                                    OFFSET 0 LIMIT :valor3');
           $stmt->execute(['valor3' => $variable3]);
           $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

           foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
               echo $v;
           }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    ?>
</body>
</html>