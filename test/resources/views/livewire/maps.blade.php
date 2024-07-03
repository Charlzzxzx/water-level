<div>
    <div x-data="{ lumbayao: false, batangan: false, isBatanganHovered: false, isLumbayaoHovered: false }" x-cloak class="container py-4 px-4 lg:px-0 mx-auto lg:w-1/1">
        <div class="grid grid-cols-1 gap-y-3">
            <div class="space-y-3 relative">
                <div class="p-2 flex justify-center">
                    <h1 class="text-2xl font-semibold">WATER LEVEL MONITORING SYSTEM</h1>
                </div>
                <div class="p-2 flex justify-center">
                    <button type="button" data-bs-target="#addDataModal" data-bs-toggle="modal"
                        class="add-data-button shadow-xl text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        style="margin-right: -520px;">
                        Add Location
                    </button>
                </div>
                <div class="p-2 flex justify-center relative">
                    <div class="w-full md:w-3/4 lg:w-2/3 xl:w-1/2 relative">
                        <div id="imageOverlay" class="shadow-lg relative">

                            <div id="map" style="width: 100%; height: 500px;"></div>


                            <div class="absolute top-0 right-2 flex flex-col cursor-pointer z-5"
                                style="padding-top: 20px;">
                                <div class="bg-white p-4 rounded-md">
                                    <div class="flex mb-2">
                                        <div class="bg-red-500 h-5 w-5 rounded-full text-white flex items-center justify-center cursor-pointer opacity-100"
                                            onclick="showAlert('Forced Evacuation', 'red')"></div>
                                        <p class="text-xs ml-2">Forced Evacuation</p>
                                    </div>

                                    <div class="flex mb-2">
                                        <div class="bg-yellow-500 h-5 w-5 rounded-full text-white flex items-center justify-center cursor-pointer opacity-100"
                                            onclick="showAlert('Alert Level', 'blue')"></div>
                                        <p class="text-xs ml-2">Alert Level</p>
                                    </div>

                                    <div class="flex">
                                        <div class="bg-green-500 h-5 w-5 rounded-full text-white flex items-center justify-center cursor-pointer opacity-100"
                                            onclick="showAlert('Normal Level', 'green')"></div>
                                        <p class="text-xs ml-2">Normal Level</p>
                                    </div>
                                </div>
                            </div>

                            <div id="searchDiv" class="absolute top-0 left-2 flex flex-col cursor-pointer z-5"
                                style="padding-top: 10px;display:none;width:100%;">
                                <div class="bg-white rounded-md" style="height:100%;">
                                    <button onclick="showMenu()" style="margin: 5px"
                                        class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">X</button>
                                    <br>
                                    {{--  <input type="text" style="width: 85%;margin-left: 10px;"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            name="" id="" placeholder="Search Place"> --}}
                                    @foreach ($records as $index => $item)
                                        <button class="btn"
                                            onclick="openLocation({{ $index }},'{{ $item['location'] }}')">
                                            <div class="row btn">
                                                <div class="col-md-12 ">

                                                    @if ($tmpMarker[$item['location']]['status'] == 'green')
                                                        <img style="float:left;" src="/markerGreen.svg" alt=""
                                                            srcset="">
                                                    @endif

                                                    @if ($tmpMarker[$item['location']]['status'] == 'yellow')
                                                        <img style="float:left;" src="/markerYellow.svg" alt=""
                                                            srcset="">
                                                    @endif

                                                    @if ($tmpMarker[$item['location']]['status'] == 'red')
                                                        <img style="float:left;" src="/markerRed.svg" alt=""
                                                            srcset="">
                                                    @endif

                                                    <h3 style="float:left;margin-top: 15px;">
                                                        {{ $item['location'] }}
                                                    </h3>
                                                </div>
                                            </div>
                                        </button>
                                        <br>
                                    @endforeach
                                </div>
                            </div>

                            <div id="menu" class="absolute top-0 left-2 flex flex-col cursor-pointer z-5"
                                style="padding-top: 10px;">
                                <div class="bg-white p-4 rounded-md">
                                    <a onclick="showSearch()">
                                        <img src="/menu.svg" alt="" srcset="">
                                    </a>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>

            <livewire:components.warning-display />

            <div class="flex justify-center py-3 px-4 buttons-container">
                <div class="container">
                    <div class="row justify-content-center">
                       <center>
                        <div class="col-lg-3">
                            <select class="form-control" name="location" id="selectLocation" style="width: 250px;float: left;">
                                @foreach ($locationArr as $item)
                                    <option value="{{ $item['location'] }}">{{ $item['location'] }}</option>
                                @endforeach
                            </select>
                            <button style="float: right;" class="btn btn-primary" id="enterLocation">Proceed</button>
                            @foreach ($locationArr as $item)
                                <button id="btnOpenView{{ $item['location'] }}"
                                    onclick="toggleLocation('{{ $item['location'] }}')"
                                    data-location="{{ $item['location'] }}" type="button"
                                    class="shadow-xl text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                    style="display: none;">
                                    View
                                </button>
                            @endforeach
                        </div>
                       </center>
                    </div>
                </div>

            </div>


            <div class="detail-content">
                @foreach ($this->records as $item)
                    <div style="display: none" id="{{ $item['location'] }}Content" class="space-y-3">
                        <div class="max-w-screen-md mx-auto sm:rounded-lg bg-white border shadow-lg rounded-lg">
                            <div id="line-graph" class="w-full graph-container">
                                <canvas id="defaultChart{{ $item['location'] }}" height="100"
                                    style="max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <livewire:components.graph-default location="{{ $item['location'] }}" />
                        <livewire:components.table-default place="{{ $item['location'] }}" />
                        <div class="flex justify-center py-3">
                            <div>
                                <button wire:click="createData"
                                    class="text-white py-2 px-4 rounded-lg shadow-md bg-blue-700 font-semibold">
                                    Force Update!
                                </button>
                                <button onclick="toggleLocation('{{ $item['location'] }}')"
                                    class="text-white py-2 px-4 rounded-lg shadow-md bg-blue-700 font-semibold">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="btn" style="display: none" id="btnReload"
                onclick="{{ $this->recordToggled() }}">reload</button>
        </div>
    </div>
    <div class="modal fade " id="setLocationModal" tabindex="-1" role="dialog"
        aria-labelledby="setLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <b>SET LOCATION</b>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="form-group">
                            <label for="location">Locations:</label>
                            <select class="form-control" name="location" id="selectLocation">
                                @foreach ($locationArr as $item)
                                    <option value="{{ $item['location'] }}">{{ $item['location'] }}</option>
                                @endforeach
                            </select>
                            <br>
                            @foreach ($this->records as $item)
                                <button id="btnOpenView{{ $item['location'] }}"
                                    onclick="toggleLocation('{{ $item['location'] }}')"
                                    data-location="{{ $item['location'] }}" type="button"
                                    class="shadow-xl text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                    style="display: none;">
                                    View
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="color:white !important;" id="btnCloseLocation"
                        onclick="onCloseModal(document.getElementById('selectLocation').value)">Proceed</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row ">
                        <form action="/" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @method('post')
                            @csrf

                            <div class="form-group text-center">
                                <b>Add Location</b>
                            </div>

                            <div class="form-group">
                                <label for="location" class="for">Location</label>
                                <INPUT class="form-control" required type="text" value="" name="location"
                                    title="Enter location" />
                            </div>
                            <div class="form-group">
                                <label for="latitude" class="for">Latitude</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="latitude" title="Enter Latitude" />
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="for">Longitude</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="longitude" title="Enter Longitude" />
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="for">Normal</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="normal" title="Enter Normal" />
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="for">Base</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="base" title="Enter Base" />
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="for">Low</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="low" title="Enter Low" />
                            </div>
                            <div class="form-group">
                                <label for="longitude" class="for">High</label>
                                <INPUT class="form-control" required step="any" type="number" value=""
                                    name="high" title="Enter High" />
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number:</label>
                                <input required type="number" name="phoneNumber" class="form-control">
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="color:white !important;">Close</button>
                    <button type="submit" class="btn btn-warning" name="btnAddData" value="yes"
                        style="color:white !important;">Proceed</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let map, activeInfoWindow, popup, markers = [];
        let directionsService;
        let directionsRenderer;
        let service;
        let listOfMarkers = [];
        let oldLocation = "";
        let markerData = @json($tmpMarker);
        var dams = @json($records);
        document.addEventListener('DOMContentLoaded', function() {
            // Function to handle the print button click
            function handlePrintButtonClick() {
                // Trigger browser's print functionality
                window.print();
            }

            const printButton = document.querySelector('.print-data-button');
            if (printButton) {
                printButton.addEventListener('click', handlePrintButtonClick);
            }

        });

        setTimeout(() => {
            const enterLocation = document.getElementById('enterLocation');
            enterLocation.addEventListener('click', function() {
                let selectLocation = document.getElementById('selectLocation').value;
                let jid = `btnOpenView${selectLocation}`;
                console.log(selectLocation);
                console.log(jid);
                let btnOpenView = document.getElementById(jid);
                btnOpenView.click();
            });
        }, 1500);

        function showMenu(location) {
            let menuDiv = document.getElementById('menu');
            menuDiv.removeAttribute("style");
            menuDiv.setAttribute("style", "padding:10px;");

            let searchDiv = document.getElementById('searchDiv');
            searchDiv.removeAttribute("style");
            searchDiv.setAttribute("style", "padding:10px;display:none;");

            let btnOpenView = document.getElementById(`btnOpenView${location}`);
            btnOpenView.click();
        }

        function showSearch() {
            let searchDiv = document.getElementById('searchDiv');
            searchDiv.removeAttribute("style");
            searchDiv.setAttribute("style", "padding:10px;width:100%;height:100%;margin-left:-10px;");

            let menuDiv = document.getElementById('menu');
            menuDiv.removeAttribute("style");
            menuDiv.setAttribute("style", "padding:10px;display:none;");
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                // navigator.geolocation.getCurrentPosition(setPosition);
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log(position);
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                }, function(err) {
                    fetch("")
                        .then(response => response.json())
                        .then(data => {
                            userLocation = {
                                lat: data.latitude,
                                lng: data.longitude
                            };
                        })
                        .catch(error => console.error("Error:", error));
                }, {
                    timeout: 50000
                });
            }


        }

        function checkMarker(status) {
            if (status == "green") {
                return "/markerGreen.svg";
            } else if (status == "yellow") {
                return "/markerYellow.svg";
            } else if (status == "red") {
                return "/markerRed.svg";
            }
        }



        function initMap() {
            try {
                getCurrentLocation();
                let infowindow = new google.maps.InfoWindow();
                const geocoder = new google.maps.Geocoder();
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer();
                let defaultLatLng = {
                    lat: 7.9022943,
                    lng: 125.0881456,
                };

                let firstItem = [];

                if (dams.length > 0) {
                    firstItem = dams[0];
                    defaultLatLng = {
                        lat: parseFloat(firstItem.latitude),
                        lng: parseFloat(firstItem.longitude),
                    };
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: defaultLatLng,
                        zoom: 17,
                        mapTypeId: 'satellite',
                        zoomControl: true,
                        mapTypeControl: false,
                        scaleControl: true,
                        streetViewControl: true,
                        rotateControl: true,
                        fullscreenControl: false
                    });
                    service = new google.maps.places.PlacesService(map);

                    dams.forEach((element) => {
                        let newLatLng = {
                            lat: parseFloat(element.latitude),
                            lng: parseFloat(element.longitude),
                        }

                        geocoder.geocode({
                            location: newLatLng
                        }, function(results, status) {
                            if (status === 'OK') {
                                if (results[0]) {
                                    const placeId = results[0].place_id;
                                    const request = {
                                        placeId: placeId,
                                        fields: ['name', 'formatted_address', 'geometry', 'photo',
                                            'rating', 'user_ratings_total'
                                        ]
                                    };
                                    let mColor = checkMarker(element.status);
                                    console.log(mColor);
                                    service.getDetails(request, function(place, status) {
                                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                                            map.setZoom(17);
                                            let myMarker = new google.maps.Marker({
                                                position: newLatLng,
                                                map: map,
                                                icon: mColor
                                            });
                                            map.setCenter(myMarker.position)
                                            myMarker.addListener('mouseover', function() {
                                                infowindow.open(map, myMarker);
                                                infowindow.setContent(
                                                    "<div class='infowindow-container'>" +
                                                    "<img src='" + (place
                                                        .photos &&
                                                        place.photos[0] ? place.photos[
                                                            0].getUrl({
                                                            maxWidth: 350,
                                                            maxHeight: 150
                                                        }) : '') +
                                                    "'></img><div class='inner'><h4>" +
                                                    element.location +
                                                    "</h4><p>" + (newLatLng
                                                        .lat & newLatLng.lng ?
                                                        `Latitude: ${newLatLng.lat}, Longitude:${newLatLng.lng}` :
                                                        'N/A') +
                                                    "</p></div></div>");
                                            });
                                            myMarker.addListener("mouseout", function() {
                                                infowindow.close();
                                            });
                                            myMarker.addListener("click", () => {
                                                map.setZoom(17);
                                                map.setCenter(myMarker.position);
                                                // infowindow.open(map, myMarker);
                                                let btnOpenDataLocation = document
                                                    .getElementById(
                                                        `btnOpenView${element.location}`);
                                                btnOpenDataLocation.click();

                                            });
                                            listOfMarkers.push(myMarker);
                                        }
                                    });
                                } else {
                                    infowindow = new google.maps.InfoWindow();
                                    console.log('No results found');
                                    let myMarker = new google.maps.Marker({
                                        map: map,
                                        position: newLatLng,
                                        title: element.location // Set a title for the marker
                                    });

                                    myMarker.addListener("click", () => {
                                        map.setZoom(17);
                                        map.setCenter(myMarker.position);
                                        infowindow.open(map, myMarker);
                                    });

                                    listOfMarkers.push(myMarker);
                                }
                            } else {
                                console.log('Geocoder failed due to: ' + status);
                            }
                        });

                        // let myMarker = new google.maps.Marker({
                        //     map: map,
                        //     position: newLatLng,
                        //     title: element.location // Set a title for the marker
                        // });

                        // myMarker.addListener("click", () => {
                        //     map.setZoom(17);
                        //     map.setCenter(myMarker.position);
                        //     infowindow.open(map, myMarker);
                        // });

                        return element;
                    });

                } else {
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: defaultLatLng,
                        zoom: 17,
                        mapTypeId: 'satellite',
                        zoomControl: true,
                        mapTypeControl: false,
                        scaleControl: true,
                        streetViewControl: true,
                        rotateControl: true,
                        fullscreenControl: false
                    });

                    centerMarker = new google.maps.Marker({
                        map: map,
                        position: defaultLatLng,
                        title: "sample" // Set a title for the marker
                    });
                }


                map.setCenter(defaultLatLng);


            } catch (e) {
                console.log(e);
            }
        }


        function openLocation(index, location) {
            showMenu(location);
            if (listOfMarkers.length > 0) {

                let sMarker = listOfMarkers[index];
                console.log("sMarker", sMarker);
                google.maps.event.trigger(sMarker, 'click');

            }
        }


        function onCloseModal(location) {
            console.log(location);
            toggleLocation(location);
        }



        function toggleLocation(location) {
            dams.forEach(element => {
                try {
                    let el = document.getElementById(`${element.location}Content`);
                    if (element.location == location && oldLocation != location) {
                        el.removeAttribute("style");
                        oldLocation = element.location;
                    } else {
                        el.removeAttribute("style");
                        el.setAttribute("style", "display:none;")
                    }

                } catch (e) {
                    console.log(e)
                }
            });
        }

        function reload() {
            window.location = "/";
        }

        //setInterval(reload, 30000); // Reload every 30 seconds
    </script>

    @if (session()->pull('errorUpdateStatus'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Failed To Update Status, Please Try Again Later',
                    showConfirmButton: true
                });
            }, 500);
        </script>
        {{ session()->forget('errorUpdateStatus') }}
    @endif

    @if (session()->pull('errorAdd'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Failed To Add Data, Please Try Again Later',
                    showConfirmButton: false,
                    timer: 800
                });
            }, 500);
        </script>
        {{ session()->forget('errorAdd') }}
    @endif

    @if (session()->pull('successAdd'))
        <script>
            setTimeout(() => {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Successfully Added Data'
                    showConfirmButton: false,
                    timer: 800
                });
            }, 500);
        </script>
        {{ session()->forget('successAdd') }}
    @endif
</div>
