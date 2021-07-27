<!DOCTYPE html>
<html>
    <head>
    </head>
<body>
    <h3><a href="http://grupo03.cc3201.dcc.uchile.cl/inicio.html">pagina inicial</a></h3>
    <h1>Consulta 1:</h1>
    <?php
        echo "<table>";
        echo "<tr>
                <th>Numero de jugadores</th>
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
           $variable1=$_GET['input1'];
           $stmt = $pdo->prepare('SELECT COUNT(player_id)
                                FROM(
                                SELECT DISTINCT GP.player_id
                                FROM (SELECT season, player_id, (CAST(wins AS FLOAT) /total)*100 AS win_percentage
                                FROM game_player) GP, player P
                                WHERE P.player_id = GP.player_id
                                AND GP.win_percentage >= 90
                                AND P.height <:valor1
                                AND GP.season BETWEEN 20102011 AND 20182019) T');
           $stmt->execute(['valor1' => $variable1]);
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