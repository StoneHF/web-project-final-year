<?php
session_start();
include 'config/conn.php';
include 'adminCheck/navbarCheck.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            width: 90%; /* set a fixed width */
            margin: 0 auto; /* center the container */
        }

        .gallery {
            margin: 20px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 10px;
            width: 300px;
            position: relative;
        }

        .gallery:hover {
            border: 1px solid #777;
        }

        .gallery img {
            max-width: 100%;
            max-height: 300px;
            object-fit: cover;
            object-position: center;
        }

        .desc {
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }

        /* Additional styles for the modal */
        .modal-bio {
            text-align: left;
            white-space: pre-line;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.gallery a').on('click', function (e) {
                e.preventDefault(); // Prevent the default link behavior

                var title = $(this).data('title');
                var bio = $(this).data('bio');

                // Update the modal content
                $('#galleryModalLabel').text(title);
                $('#galleryModal .modal-bio').text(bio);

                $('#galleryModal').modal('show');
            });
        });

        function closeGalleryModal() {
            $('#galleryModal').modal('hide');
        }
    </script>
</head>
<body>
    <!-- Create the gallery container -->
    <div class="gallery-container">
        <?php
            // Your PHP code to generate gallery items goes here
            $sql = "SELECT * FROM `tbl_game_release_img` ORDER BY `fldReleaseDate` ASC";
            $result = $mysqli->query($sql);

            while ($row = $result->fetch_assoc()) {
                echo '<div class="gallery">';
                echo '<a href="#" data-title="' . $row['fldImageTitle'] . '" data-bio="' . $row['fldBio'] . '">';
                echo '<img src="' . $row['fldPath'] . '" alt="' . $row['fldImageTitle'] . '" width="600" height="400">';
                echo '<div class="desc"><span class="asc-title"  style="color: white;">Platforms: ' . getPlatforms($row) . '</span></div>';
                echo '<div class="desc"><span class="asc-title" style="color: white;">Release Date: ' . $row['fldReleaseDate'] . '</span></div>';
                echo '</a>';
                echo '</div>';
            }

            function getPlatforms($row) {
                $platforms = array();
                if ($row['fldXboxOne'] == 1) {
                    $platforms[] = 'Xbox One';
                }
                if ($row['fldXboxSX'] == 1) {
                    $platforms[] = 'Xbox Series X';
                }
                if ($row['fldPlaystation4'] == 1) {
                    $platforms[] = 'Playstation 4';
                }
                if ($row['fldPlaystation5'] == 1) {
                    $platforms[] = 'Playstation 5';
                }
                if ($row['fldPC'] == 1) {
                    $platforms[] = 'PC';
                }
                if ($row['fldSwitch'] == 1) {
                    $platforms[] = 'Switch';
                }
                return implode(', ', $platforms);
            }
        ?>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModalLabel"></h5>
                </div>
                <div class="modal-body modal-bio"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeGalleryModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
