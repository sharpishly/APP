{{{header}}}

<!-- Trade Only -->
<div class="layout-item">
<h1>{{{h1}}}</h1>
<h2>{{{h2}}}</h2>
<section id="trade-only" class="py-12">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b-2 border-gray-300 pb-2">Trade Only</h2>
    <p class="mb-4">
        Welcome to the Trade Only section. Here, businesses can access suppliers and decorators for merchandising and promotional needs.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-blue-600 mb-4">Suppliers</h3>
            <p class="text-gray-700 mb-4">
                Find suppliers of white-label products.
            </p>
            <ul class="list-none">
                <li class="mb-2">
                    <strong>White-Label Cups:</strong> £[Cost]/unit
                    <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Supplier</a>
                </li>
                <li class="mb-2">
                    <strong>White-Label Mugs:</strong> £[Cost]/unit
                     <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Supplier</a>
                </li>
                <li class="mb-2">
                    <strong>White-Label Bottles:</strong> £[Cost]/unit
                     <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Supplier</a>
                </li>
                 <li class="mb-2">
                    <strong>White-Label T-Shirts:</strong> £[Cost]/unit
                     <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Supplier</a>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-green-600 mb-4">Decorators</h3>
            <p class="text-gray-700 mb-4">
                Get your products decorated with your logo or design.
            </p>
            <ul class="list-none">
                <li class="mb-2">
                    <strong>Cup Decoration:</strong> £[Cost]/unit
                    <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Decorator</a>
                </li>
                <li class="mb-2">
                    <strong>Mug Decoration:</strong> £[Cost]/unit
                    <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Decorator</a>
                </li>
                <li class="mb-2">
                    <strong>Bottle Decoration:</strong> £[Cost]/unit
                     <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Decorator</a>
                </li>
                 <li class="mb-2">
                    <strong>T-Shirt Decoration:</strong> £[Cost]/unit
                     <a href="#" class="text-blue-500 hover:underline ml-2 text-sm">Contact Decorator</a>
                </li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-purple-600 mb-4">E-commerce Calculator</h3>
            <p class="text-gray-700 mb-4">
               Calculate the total cost for your order.
            </p>
            <div class="mb-4">
                <label for="product" class="block text-gray-700 text-sm font-bold mb-2">Product:</label>
                <select id="product" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="cup">Cup</option>
                    <option value="mug">Mug</option>
                    <option value="bottle">Bottle</option>
                    <option value="shirt">T-Shirt</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                <input type="number" id="quantity" value="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="packaging" class="block text-gray-700 text-sm font-bold mb-2">Packaging:</label>
                <select id="packaging" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="basic">Basic</option>
                    <option value="giftbox">Gift Box</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="delivery" class="block text-gray-700 text-sm font-bold mb-2">Delivery:</label>
                <select id="delivery" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="local">Local Pickup</option>
                    <option value="standard">Standard Delivery</option>
                    <option value="express">Express Delivery</option>
                </select>
            </div>

            <p class="text-xl font-semibold text-gray-800 mb-4">Total Cost: <span id="total-cost" class="text-green-600">£0.00</span></p>
            <button id="calculate-cost" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Calculate</button>
             <div id="cost-breakdown" class="mt-4 p-4 bg-gray-50 rounded-md border border-gray-200 hidden">
                <h4 class="font-semibold text-lg mb-2">Cost Breakdown</h4>
                <ul class="list-disc list-inside space-y-1 text-gray-700">
                    <li id="supply-cost-breakdown">Supply Cost: £0.00</li>
                    <li id="decoration-cost-breakdown">Decoration Cost: £0.00</li>
                    <li id="packaging-cost-breakdown">Packaging Cost: £0.00</li>
                    <li id="delivery-cost-breakdown">Delivery Cost: £0.00</li>
                </ul>
            </div>
        </div>
    </div>
</section>
</div>
<!-- Trade Only -->


{{{footer}}}