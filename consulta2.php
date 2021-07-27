<!DOCTYPE html>
<html>
    <head>
        <title>Consulta2</title>
    </head>
<body>
    <h3><a href="http://grupo03.cc3201.dcc.uchile.cl/inicio.html">pagina inicial</a></h3>
    <h1>Consulta 2:</h1>

    <?php
        echo "<table>";
        echo "<tr>
                <th>Temporada</th>
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
           $variable2=$_GET['input2'];
           $stmt = $pdo->prepare('SELECT *
                                FROM (
                                    SELECT T.season, SUM(T.goals) AS goals_totals
                                    FROM (SELECT G.season, GTS.goals
                                    FROM game_teams_stats GTS, game G
                                    WHERE GTS.game_id = G.game_id
                                    AND GTS.goals != -1) T
                                    GROUP BY T.season) U
                                    WHERE goals_totals >:valor2
                                    ORDER BY U.goals_totals DESC');
           $stmt->execute(['valor2' => $variable2]);
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