<?php
$products = $data['products'];
$address = $data['address'];
$shippingCost = $data['shipping_cost'];

// @todo: Calculate the subtotal from the products array
$subtotal = 0;
foreach ($products as $product) {
    $subtotal += $product['qty'] * $product['price'];
}

$total = $subtotal + $shippingCost;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ollyo Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./resources/css/style.css">
</head>

<body>
    <div class="max-w-[1200px] mx-auto my-20">
        <div class="w-full bg-white shadow my-10 p-6 rounded-xl flex items-center justify-center">
            <a href="/">
                <svg xmlns="http://www.w3.org/2000/svg" width="78" height="24" fill="none">
                    <path fill="#000" d="M38.47 20.047c2.754-1.13 4.518-3.812 4.448-6.847 0-1.976-.777-3.812-2.118-5.224-1.341-1.411-3.247-2.188-5.153-2.117-2.965 0-5.576 1.765-6.706 4.588-1.13 2.753-.494 5.93 1.553 8.047 2.118 2.118 5.224 2.753 7.977 1.553Zm-2.823-2.823c-2.047 0-3.67-1.624-3.67-3.953 0-2.33 1.623-3.953 3.67-3.953 2.047 0 3.67 1.623 3.67 3.953 0 2.329-1.623 3.953-3.67 3.953Zm37.977 3.035a5.33 5.33 0 0 0-1.977-10.236c-2.118 0-4.023 1.271-4.87 3.318-.777 1.977-.353 4.306 1.129 5.788 1.553 1.483 3.741 1.906 5.718 1.13Zm-2.047-2.612c-1.271 0-2.26-.988-2.26-2.33 0-1.411.989-2.329 2.26-2.329 1.27 0 2.258.988 2.258 2.33 0 1.34-.988 2.329-2.258 2.329ZM48.212 5.506V20.47h-3.883V5.506h3.883Zm5.788 0V20.47h-3.67V5.506H54Zm5.153 12.353-4.165-7.906h3.883l2.188 4.306 2.188-4.306h3.812L59.435 24h-3.74l3.458-6.141Z"></path>
                    <path fill="#0BC1C0" d="M4.447 9.459c0-2.33.847-4.447 2.259-6.141a10.334 10.334 0 0 0 3.741 19.976c4.447 0 8.259-2.823 9.741-6.776a9.407 9.407 0 0 1-6.211 2.329 9.371 9.371 0 0 1-9.53-9.388ZM13.906 0c-2.894 0-5.435 1.27-7.2 3.318a10.376 10.376 0 0 1 14.117 9.6c0 1.27-.211 2.47-.635 3.53a9.297 9.297 0 0 0 3.247-7.06C23.365 4.235 19.13 0 13.905 0Z"></path>
                </svg>
            </a>
        </div>
        <form method="post" action="/checkout" class="space-y-8">
            <div class="grid grid-cols-[auto_350px] gap-8">
                <div class="space-y-4">
                    <?php foreach ($products as $product) : ?>
                        <div class="bg-white shadow p-4 flex gap-4 rounded-md">
                            <div class="w-[96px] h-[96px] relative rounded-md overflow-hidden">
                                <img src="<?php echo $product['image']; ?>" class="absolute top-0 left-0 w-full h-full object-cover" />
                            </div>
                            <div class="space-y-2">
                                <h3 class="font-medium"><?php echo $product['name']; ?></h3>
                                <p class="text-sm text-gray-500"><?php echo '$' . $product['price']; ?></p>
                                <p class="text-sm text-gray-500">Qty: <?php echo $product['qty']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="bg-white shadow p-4 rounded-md space-y-4 h-fit">
                    <h4 class="text-lg font-semibold">Order Summary</h4>
                    <div class="space-y-2">
                        <div class="text-sm text-gray-500 flex items-center justify-between">
                            <p>Subtotal</p>
                            <p>$<?php echo $subtotal; ?></p>
                        </div>
                        <div class="text-sm text-gray-500 flex items-center justify-between">
                            <p>Shipping</p>
                            <p>$<?php echo $shippingCost; ?></p>
                        </div>
                    </div>
                    <div class="w-full h-[1px] bg-gray-300"></div>
                    <div class="font-semibold flex items-center justify-between">
                        <p>Total</p>
                        <p>$<?php echo $total; ?></p>
                    </div>
                    <button class="inline-flex w-full items-center justify-center gap-2 text-sm font-medium whitespace-nowrap rounded-md transition-colors ring-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-gray-800 text-white hover:bg-gray-700/90 h-9 px-4 py-2">Place Order</button>
                </div>
            </div>
            <div class="grid grid-cols-[auto_calc(350px_+2rem)]">
                <div>
                    <h2 class="font-semibold text-xl">Shipping Information</h2>
                    <div class="space-y-4 mt-4">
                        <div class="space-y-1 flex flex-col">
                            <label class="text-sm text-gray-800 font-medium" for="name">Full Name</label>
                            <input type="text" name="name" placeholder="Enter your full name" value="<?php echo $address['name']; ?>" id="name" class="bg-white px-2 text-sm rounded-md border border-gray-300 h-10 placeholder:text-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2">
                        </div>
                        <div class="space-y-1 flex flex-col">
                            <label class="text-sm text-gray-800 font-medium" for="name">Email</label>
                            <input type="email" name="email" placeholder="Enter your email" value="<?php echo $address['email']; ?>" id="email" class="bg-white px-2 text-sm rounded-md border border-gray-300 h-10 placeholder:text-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2">
                        </div>
                        <div class="space-y-1 flex flex-col">
                            <label class="text-sm text-gray-800 font-medium" for="address">Address</label>
                            <input type="text" name="address" placeholder="Enter your address" id="address" value="<?php echo $address['address']; ?>" class="bg-white px-2 text-sm rounded-md border border-gray-300 h-10 placeholder:text-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1 flex flex-col">
                                <label class="text-sm text-gray-800 font-medium" for="city">City</label>
                                <input type="text" name="city" placeholder="Enter your city" id="city" value="<?php echo $address['city']; ?>" class="bg-white px-2 text-sm rounded-md border border-gray-300 h-10 placeholder:text-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2">
                            </div>
                            <div class="space-y-1 flex flex-col">
                                <label class="text-sm text-gray-800 font-medium" for="postal-code">Postal Code</label>
                                <input type="text" name="post_code" placeholder="Enter postal code" id="postal-code" value="<?php echo $address['post_code']; ?>" class="bg-white px-2 text-sm rounded-md border border-gray-300 h-10 placeholder:text-sm placeholder:text-gray-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-700 focus-visible:ring-offset-2">
                            </div>
                        </div>
                    </div>
                </div>
                <div></div>
            </div>
        </form>
    </div>
</body>

</html>