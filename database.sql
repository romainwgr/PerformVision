-- Dropping existing tables if they exist to avoid conflicts
DROP TABLE
IF
  EXISTS DailyHours CASCADE;
  DROP TABLE
  IF
    EXISTS BDL CASCADE;
    DROP TABLE
    IF
      EXISTS Represente CASCADE;
      DROP TABLE
      IF
        EXISTS Affecte CASCADE;
        DROP TABLE
        IF
          EXISTS Composante CASCADE;
          DROP TABLE
          IF
            EXISTS Adresse CASCADE;
            DROP TABLE
            IF
              EXISTS Gestionnaire CASCADE;
              DROP TABLE
              IF
                EXISTS Commercial CASCADE;
                DROP TABLE
                IF
                  EXISTS Client CASCADE;
                  DROP TABLE
                  IF
                    EXISTS Interlocuteur CASCADE;
                    DROP TABLE
                    IF
                      EXISTS Prestataire CASCADE;
                      DROP TABLE
                      IF
                        EXISTS Administrateur CASCADE;
                        DROP TABLE
                        IF
                          EXISTS Personne CASCADE;
                          DROP TABLE
                          IF
                            EXISTS Absences CASCADE;


                            -- Table Personne
                            CREATE TABLE Personne(
                              id_personne SERIAL PRIMARY KEY
                              , mot_de_passe VARCHAR(255) NOT NULL
                              , nom VARCHAR(50)
                              , prenom VARCHAR(50)
                              , mail VARCHAR(255) NOT NULL
                              , telephone VARCHAR(50)
                            );

                            -- Table Prestataire
                            CREATE TABLE Prestataire(
                              id_personne INT PRIMARY KEY
                              , FOREIGN KEY(id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );

                            -- Table Interlocuteur
                            CREATE TABLE Interlocuteur(
                              id_personne INT PRIMARY KEY
                              , FOREIGN KEY(id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );

                            -- Table Client
                            CREATE TABLE Client(
                              id_client SERIAL PRIMARY KEY
                              , nom_client VARCHAR(50)
                              , telephone_client VARCHAR(50)
                            );

                            -- Table Commercial
                            CREATE TABLE Commercial(
                              id_personne INT PRIMARY KEY
                              , FOREIGN KEY(id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );

                            -- Table Gestionnaire
                            CREATE TABLE Gestionnaire(
                              id_personne INT PRIMARY KEY
                              , FOREIGN KEY(id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );

                            -- Table Adresse
                            CREATE TABLE Adresse(
                              id_adresse SERIAL PRIMARY KEY
                              , adresse VARCHAR(255)
                              , code_postal VARCHAR(50)
                              , ville VARCHAR(50)
                              , type_de_voie VARCHAR(50)
                            );

                            -- Table Composante
                            CREATE TABLE Composante(
                              id_composante SERIAL PRIMARY KEY
                              , nom_composante VARCHAR(50)
                              , id_adresse INT NOT NULL
                              , id_client INT NOT NULL
                              , FOREIGN KEY(id_adresse) REFERENCES Adresse(id_adresse)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_client) REFERENCES Client(id_client)
                              ON DELETE CASCADE
                            );

                            -- Table BDL (Bon de Livraison)
                            CREATE TABLE BDL(
                              id_bdl SERIAL PRIMARY KEY
                              , id_composante INT
                              , id_prestataire INT
                              , annee INT
                              , mois VARCHAR(50)
                              , signature_interlocuteur BOOLEAN
                              , signature_prestataire BOOLEAN
                              , commentaire VARCHAR(50)
                              , heures DECIMAL(15, 2)
                              , id_interlocuteur INT NOT NULL
                              , id_gestionnaire INT
                              , FOREIGN KEY(id_composante) REFERENCES Composante(id_composante)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_prestataire) REFERENCES Prestataire(id_personne)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_interlocuteur) REFERENCES Interlocuteur(id_personne)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_gestionnaire) REFERENCES Gestionnaire(id_personne)
                              ON DELETE CASCADE
                              , CONSTRAINT unique_bdl_combination UNIQUE (id_composante, id_prestataire, annee, mois)
                            );

                            -- Table Represente
                            CREATE TABLE Represente(
                              id_personne INT
                              , id_composante INT
                              , PRIMARY KEY(id_personne, id_composante)
                              , FOREIGN KEY(id_personne) REFERENCES Interlocuteur(id_personne)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_composante) REFERENCES Composante(id_composante)
                              ON DELETE CASCADE
                            );

                            -- Table Affecte
                            CREATE TABLE Affecte(
                              id_personne INT
                              , id_composante INT
                              , PRIMARY KEY(id_personne, id_composante)
                              , FOREIGN KEY(id_personne) REFERENCES Commercial(id_personne)
                              ON DELETE CASCADE
                              , FOREIGN KEY(id_composante) REFERENCES Composante(id_composante)
                              ON DELETE CASCADE
                            );

                            -- Table Administrateur
                            CREATE TABLE Administrateur(
                              id_personne INT PRIMARY KEY
                              , FOREIGN KEY (id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );

                            -- Table DailyHours
                            CREATE TABLE DailyHours (
                              id SERIAL PRIMARY KEY
                              , id_bdl INT
                              , jour INT
                              , hours_worked DECIMAL(5, 2)
                              , FOREIGN KEY(id_bdl) REFERENCES BDL(id_bdl)
                              ON DELETE CASCADE
                            );

                            -- Table Absences
                            CREATE TABLE Absences (
                              id SERIAL PRIMARY KEY
                              , id_personne INT NOT NULL
                              , date_absence DATE NOT NULL
                              , motif TEXT NOT NULL
                              , FOREIGN KEY(id_personne) REFERENCES Personne(id_personne)
                              ON DELETE CASCADE
                            );
                            -- Inserting data into tables

                            -- Inserting data into Personne
                            INSERT INTO
                              Personne (mot_de_passe, nom, prenom, mail, telephone)
                            VALUES
                              (
                                'mdp123'
                                , 'Wagner'
                                , 'Romain'
                                , 'romain.wagner@example.com'
                                , '0663473381'
                              )
                              , (
                                'mdp456'
                                , 'Smith'
                                , 'Jane'
                                , 'jane.smith@example.com'
                                , '0687654321'
                              )
                              , (
                                'password123'
                                , 'Lemoine'
                                , 'Alice'
                                , 'alice.lemoine@example.com'
                                , '0612345678'
                              )
                              , (
                                'password456'
                                , 'Dubois'
                                , 'Marc'
                                , 'marc.dubois@example.com'
                                , '0698765432'
                              )
                              , (
                                'password789'
                                , 'Rousseau'
                                , 'Claire'
                                , 'claire.rousseau@example.com'
                                , '0712345678'
                              )
                              , (
                                'password101'
                                , 'Moreau'
                                , 'Paul'
                                , 'paul.moreau@example.com'
                                , '0723456789'
                              )
                              , (
                                'password112'
                                , 'Fournier'
                                , 'Julie'
                                , 'julie.fournier@example.com'
                                , '0734567890'
                              )
                              , (
                                'password113'
                                , 'Blanc'
                                , 'Thomas'
                                , 'thomas.blanc@example.com'
                                , '0745678901'
                              )
                              , (
                                'safe999'
                                , 'Clark'
                                , 'Kent'
                                , 'clark.kent@dailyplanet.com'
                                , '0676543210'
                              )
                              , (
                                'superSecure'
                                , 'Wayne'
                                , 'Bruce'
                                , 'bruce.wayne@wayneenterprises.com'
                                , '062837465'
                              );

                            -- Assuming IDs 1 to 10 for the above inserted personnes
                            -- Inserting data into Prestataire
                            INSERT INTO
                              Prestataire (id_personne)
                            VALUES
                              (1)
                              , (7)
                              , (8);

                            -- Inserting data into Interlocuteur
                            INSERT INTO
                              Interlocuteur (id_personne)
                            VALUES
                              (2)
                              , (9)
                              , (10);

                            -- Inserting data into Client
                            INSERT INTO
                              Client (nom_client, telephone_client)
                            VALUES
                              ('Client A', '0631231234')
                              , ('Client B', '0613214321')
                              , ('Entreprise C', '0145678910')
                              , ('Entreprise D', '0156789021');

                            -- Inserting data into Commercial
                            INSERT INTO
                              Commercial (id_personne)
                            VALUES
                              (1)
                              , (6)
                              , (3);

                            -- Inserting data into Gestionnaire
                            INSERT INTO
                              Gestionnaire (id_personne)
                            VALUES
                              (8)
                              , (10)
                              , (4);

                            -- Inserting data into Adresse
                            INSERT INTO
                              Adresse (adresse, code_postal, ville, type_de_voie)
                            VALUES
                              ('1234 Main St', '90001', 'Metropolis', 'Avenue')
                              , ('4321 Second St', '10001', 'Gotham', 'Boulevard')
                              , ('789 Rue des Fleurs', '75003', 'Paris', 'Rue')
                              , ('321 Avenue des Champs', '69003', 'Lyon', 'Avenue');

                            -- Inserting data into Composante
                            INSERT INTO
                              Composante (nom_composante, id_adresse, id_client)
                            VALUES
                              ('Composante 1', 1, 1)
                              , ('Composante 2', 2, 2)
                              , ('Composante 3', 3, 3)
                              , ('Composante 4', 4, 4);

                            -- Inserting data into Represente
                            INSERT INTO
                              Represente (id_personne, id_composante)
                            VALUES
                              (2, 1)
                              , (9, 3)
                              , (10, 4);

                            -- Inserting data into Affecte
                            INSERT INTO
                              Affecte (id_personne, id_composante)
                            VALUES
                              (3, 1)
                              , (1, 3)
                              , (6, 4);

                            -- Inserting data into BDL

                            INSERT INTO
                              BDL (
                                id_composante
                                , id_prestataire
                                , annee
                                , mois
                                , signature_interlocuteur
                                , signature_prestataire
                                , commentaire
                                , heures
                                , id_interlocuteur
                                , id_gestionnaire
                              )
                            VALUES
                              (
                                1
                                , 1
                                , 2024
                                , 'January'
                                , TRUE
                                , TRUE
                                , 'No issues'
                                , 120.5
                                , 2
                                , 4
                              )
                              , (3, 7, 2023, 'Mars', TRUE, FALSE, 'RAS', 100.00, 9, 8)
                              , (
                                4
                                , 8
                                , 2023
                                , 'Avril'
                                , FALSE
                                , TRUE
                                , 'A vérifier'
                                , 90.50
                                , 10
                                , 10
                              )
                              -- For prestataire 1
                              (
                                1
                                , 1
                                , 2024
                                , 'February'
                                , TRUE
                                , TRUE
                                , 'Meeting scheduled'
                                , 110.0
                                , 2
                                , 4
                              )
                              , (
                                2
                                , 1
                                , 2024
                                , 'March'
                                , TRUE
                                , FALSE
                                , 'Follow up needed'
                                , 95.5
                                , 9
                                , 8
                              )
                              , (
                                3
                                , 1
                                , 2024
                                , 'April'
                                , FALSE
                                , TRUE
                                , 'Project delayed'
                                , 88.0
                                , 10
                                , 10
                              )
                              , (4, 1, 2024, 'May', TRUE, TRUE, 'All good', 130.0, 2, 4)
                              , (
                                1
                                , 1
                                , 2024
                                , 'June'
                                , TRUE
                                , TRUE
                                , 'Monthly report submitted'
                                , 140.0
                                , 9
                                , 8
                              )
                              , -- For prestataire 7
                                (
                                2
                                , 7
                                , 2024
                                , 'January'
                                , TRUE
                                , TRUE
                                , 'Initial setup'
                                , 120.0
                                , 2
                                , 4
                              )
                              , (
                                3
                                , 7
                                , 2024
                                , 'February'
                                , TRUE
                                , FALSE
                                , 'System maintenance'
                                , 105.5
                                , 9
                                , 8
                              )
                              , (
                                4
                                , 7
                                , 2024
                                , 'March'
                                , FALSE
                                , TRUE
                                , 'Software update'
                                , 92.0
                                , 10
                                , 10
                              )
                              , (
                                1
                                , 7
                                , 2024
                                , 'April'
                                , TRUE
                                , TRUE
                                , 'Performance review'
                                , 115.0
                                , 2
                                , 4
                              )
                              , (
                                2
                                , 7
                                , 2024
                                , 'May'
                                , TRUE
                                , TRUE
                                , 'Annual audit'
                                , 125.0
                                , 9
                                , 8
                              )
                              , -- For prestataire 8
                                (
                                3
                                , 8
                                , 2024
                                , 'January'
                                , TRUE
                                , TRUE
                                , 'Network upgrade'
                                , 110.0
                                , 2
                                , 4
                              )
                              , (
                                4
                                , 8
                                , 2024
                                , 'February'
                                , TRUE
                                , FALSE
                                , 'Security check'
                                , 95.0
                                , 9
                                , 8
                              )
                              , (
                                1
                                , 8
                                , 2024
                                , 'March'
                                , FALSE
                                , TRUE
                                , 'New project launch'
                                , 88.5
                                , 10
                                , 10
                              )
                              , (
                                2
                                , 8
                                , 2024
                                , 'April'
                                , TRUE
                                , TRUE
                                , 'Client feedback'
                                , 120.0
                                , 2
                                , 4
                              )
                              , (
                                3
                                , 8
                                , 2024
                                , 'May'
                                , TRUE
                                , TRUE
                                , 'Service improvement'
                                , 130.5
                                , 9
                                , 8
                              );