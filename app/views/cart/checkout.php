<?php
$title = "Shop";
ob_start();
?>
<style>
    body {
        background-color: #f8f9fa;
    }
    .checkout-container {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
    }
    .section-title {
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
    }
    .hidden {
        display: none;
    }
    .loader-container {
        text-align: center;
        display: none;
    }
    .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            display: none;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
            background-color: #2196F3;
            color: white;
            animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 2.5s;
            z-index: 9999;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
    
</style>
<div class="container mt-5">
    <div class="row">      
        <div class="col-md-6">
            <div class="checkout-container">
                <h2 class="mb-4 text-primary">Checkout</h2>
                
                <form id="checkout-form">
                    <h4 class="section-title">Billing Details</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone:</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address:</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Country:</label>
                            <select name="country" id="country" class="form-control" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">State:</label>
                            <select name="state" id="state" class="form-control" required>
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City:</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Zip Code:</label>
                            <input type="text" name="zip" class="form-control" required>
                        </div>
                    </div>

                    <h4 class="section-title">Shipping Details</h4>
                    <div class="mb-3">
                        <label class="form-label">Shipping Method:</label>
                        <select name="shipping_method" id="shipping_method" class="form-control">
                            <option value="ship">Ship to Address</option>
                            <option value="pickup">Pickup</option>
                        </select>
                    </div>

                    <div id="shipping-details">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Name:</label>
                                <input type="text" name="shipping_name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Address:</label>
                                <input type="text" name="shipping_address" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Country:</label>
                                <select name="shipping_country" id="shipping_country" class="form-control">
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping State:</label>
                                <select name="shipping_state" id="shipping_state" class="form-control">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping City:</label>
                                <input type="text" name="shipping_city" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Zip Code:</label>
                                <input type="text" name="shipping_zip" class="form-control">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="orderId" value="<?php echo $_REQUEST['order_id']; ?>">
                    <button type="submit" class="btn btn-primary w-100 mt-3">Proceed to Payment</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div id="iframe-container" class="checkout-container hidden">
                <h4 class="text-primary">Payment</h4>
                <div id="loader" class="loader-container">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Processing Payment...</p>
                </div>
                <iframe id="paymentIframe" width="100%" height="800px" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://secure.paytabs.com/payment/js/payment-api.js"></script>
<script>
const countryStateData = {
    "USA": { code: "US", states: ["California", "Texas", "Florida", "New York", "Illinois", "Pennsylvania", "Ohio", "Georgia", "North Carolina", "Michigan"] },
    "Canada": { code: "CA", states: ["Ontario", "Quebec", "British Columbia", "Alberta", "Manitoba", "Saskatchewan", "Nova Scotia", "New Brunswick", "Newfoundland and Labrador"] },
    "UK": { code: "GB", states: ["England", "Scotland", "Wales", "Northern Ireland"] },
    "India": { code: "IN", states: ["Maharashtra", "Delhi", "Karnataka", "Tamil Nadu", "Gujarat", "West Bengal", "Rajasthan", "Uttar Pradesh", "Kerala"] },
    "Australia": { code: "AU", states: ["New South Wales", "Victoria", "Queensland", "Western Australia", "South Australia", "Tasmania", "Northern Territory", "Australian Capital Territory"] },
    "Germany": { code: "DE", states: ["Bavaria", "Berlin", "Hamburg", "Hesse", "Saxony", "Baden-Württemberg", "Lower Saxony", "North Rhine-Westphalia"] },
    "France": { code: "FR", states: ["Île-de-France", "Provence-Alpes-Côte d'Azur", "Occitanie", "Nouvelle-Aquitaine", "Auvergne-Rhône-Alpes", "Brittany", "Normandy"] },
    "Brazil": { code: "BR", states: ["São Paulo", "Rio de Janeiro", "Bahia", "Minas Gerais", "Paraná", "Rio Grande do Sul", "Pernambuco", "Ceará"] },
    "China": { code: "CN", states: ["Guangdong", "Beijing", "Shanghai", "Shandong", "Jiangsu", "Zhejiang", "Sichuan", "Henan"] },
    "Japan": { code: "JP", states: ["Tokyo", "Osaka", "Kyoto", "Hokkaido", "Fukuoka", "Aichi", "Kanagawa", "Hyogo"] },
    "Mexico": { code: "MX", states: ["Mexico City", "Jalisco", "Nuevo León", "Puebla", "Veracruz", "Yucatán", "Guanajuato", "Chihuahua"] },
    "South Africa": { code: "ZA", states: ["Gauteng", "Western Cape", "Eastern Cape", "KwaZulu-Natal", "Free State", "Limpopo", "Mpumalanga"] },
    "Italy": { code: "IT", states: ["Lombardy", "Lazio", "Campania", "Sicily", "Tuscany", "Veneto", "Emilia-Romagna", "Piedmont"] },
    "Russia": { code: "RU", states: ["Moscow", "Saint Petersburg", "Siberia", "Ural", "Tatarstan", "Bashkortostan", "Novosibirsk"] },
    "Argentina": { code: "AR", states: ["Buenos Aires", "Córdoba", "Santa Fe", "Mendoza", "Tucumán", "Salta", "Entre Ríos"] },
    "Spain": { code: "ES", states: ["Madrid", "Catalonia", "Andalusia", "Valencia", "Galicia", "Basque Country", "Castile and León"] },
    "UAE": { code: "AE", states: ["Dubai", "Abu Dhabi", "Sharjah", "Ras Al Khaimah", "Ajman", "Fujairah", "Umm Al Quwain"] },
    "Saudi Arabia": { code: "SA", states: ["Riyadh", "Jeddah", "Mecca", "Medina", "Dammam", "Tabuk", "Qassim", "Asir"] },
    "Egypt": { code: "EG", states: ["Cairo", "Alexandria", "Giza", "Port Said", "Suez", "Luxor", "Aswan", "Mansoura"] }
};

document.addEventListener("DOMContentLoaded", function() {
    let countrySelect = document.getElementById("country");
    let shippingCountrySelect = document.getElementById("shipping_country");

    for (let country in countryStateData) {
        let option = new Option(country, countryStateData[country].code); // Use country code as value
        countrySelect.add(option.cloneNode(true));
        shippingCountrySelect.add(option.cloneNode(true));
    }
});

document.getElementById("country").addEventListener("change", function() {
    let stateSelect = document.getElementById("state");
    updateStates(this.value, stateSelect);
});

document.getElementById("shipping_country").addEventListener("change", function() {
    let shippingStateSelect = document.getElementById("shipping_state");
    updateStates(this.value, shippingStateSelect);
});

function updateStates(countryCode, stateSelect) {
    stateSelect.innerHTML = "<option value=''>Select State</option>";
    
    let countryName = Object.keys(countryStateData).find(key => countryStateData[key].code === countryCode);
    
    if (countryName && countryStateData[countryName].states) {
        countryStateData[countryName].states.forEach(state => {
            let option = new Option(state, state);
            stateSelect.add(option);
        });
    }
}

document.getElementById("shipping_method").addEventListener("change", function() {
    let shippingDetails = document.getElementById("shipping-details");
    shippingDetails.style.display = (this.value === "pickup") ? "none" : "block";
});

document.getElementById("checkout-form").addEventListener("submit", function(e) {
    e.preventDefault();
    
    let submitButton = document.querySelector("button[type='submit']");
    let iframeContainer = document.getElementById("iframe-container");
    let iframe = document.getElementById("paymentIframe");
    let loader = document.getElementById("loader");

    submitButton.style.display = "none";
    iframeContainer.style.display = "block";
    loader.style.display = "block";
    iframe.style.display = "none";

    fetch('?page=init-payment', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect_url) {     
            iframe.onload = function() {
                loader.style.display = "none";
                iframe.style.display = "block";
            };
            document.getElementById("paymentIframe").src = data.redirect_url;
            document.getElementById("iframe-container").style.display = "block";
        } else {
            alert("Payment initialization failed!");
            submitButton.style.display = "block";
            iframeContainer.style.display = "none";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        submitButton.style.display = "block";
        iframeContainer.style.display = "none"
    });
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout/layout.php";
?>