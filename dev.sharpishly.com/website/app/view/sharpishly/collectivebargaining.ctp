
{{{header}}}

<div class="layout-item">
    <h1>{{{h1}}}</h1>
    <h2>{{{h2}}}</h2>
    <section id="collective-bargaining" class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Collective Bargaining Power</h2>
        <p class="text-lg text-gray-700 mb-8 text-center">
            Together, we have the power to negotiate better deals!  This section of our Town Portal facilitates collective bargaining
            for goods and services, allowing residents to group together and leverage their combined buying power to secure lower prices
            and better terms from vendors.
        </p>

        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h3 class="text-2xl font-semibold text-green-600 mb-4">How Collective Bargaining Works</h3>
            <ol class="list-decimal list-inside space-y-4 text-gray-700">
                <li>
                    <span class="font-semibold">1.  Proposal Initiation:</span>
                    <p>
                        A resident, group, or the Town Portal administrators can propose a collective bargaining initiative.  This involves
                        identifying a specific product or service (e.g., solar panels, internet service, home security) and setting a target
                        number of participants.
                    </p>
                </li>
                <li>
                    <span class="font-semibold">2.  Community Sign-Up:</span>
                    <p>
                        Other residents express their interest in joining the initiative by signing up through the form on this page.
                        The proposal is also promoted through the Town Portal newsletter, social media, and other communication channels.
                    </p>
                </li>
                <li>
                    <span class="font-semibold">3.  Vendor Outreach:</span>
                    <p>
                        Once a sufficient number of participants has been reached, the Town Portal, or a designated representative, will
                        reach out to potential vendors.  We will solicit bids, leveraging the collective buying power of the group to
                        negotiate favorable pricing, terms, and conditions.
                    </p>
                </li>
                <li>
                    <span class="font-semibold">4.  Bid Evaluation and Selection:</span>
                    <p>
                        The bids received from vendors will be evaluated based on price, quality, vendor reputation, and other relevant
                        factors.  The community will be informed of the top bids, and in some cases, a vote may be held to select the
                        winning bid.
                    </p>
                </li>
                <li>
                    <span class="font-semibold">5.  Agreement and Purchase:</span>
                    <p>
                        The Town Portal will facilitate the agreement between the selected vendor and the participating residents.
                        Residents will then purchase the product or service directly from the vendor, but at the pre-negotiated price.
                    </p>
                </li>
            </ol>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <h3 class="text-2xl font-semibold text-green-600 mb-4">Start or Join a Collective Bargaining Initiative</h3>
            <p class="mb-4 text-gray-700">
                Use the form below to propose a new collective bargaining initiative or to express your interest in joining an existing one.
            </p>
            <form id="collective-bargaining-form" class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Your Name:</label>
                    <input type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Your Email:</label>
                    <input type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="initiative-type" class="block text-gray-700 text-sm font-bold mb-2">Initiative Type:</label>
                    <select id="initiative-type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="new">Propose a New Initiative</option>
                        <option value="join">Join an Existing Initiative</option>
                    </select>
                </div>
                <div id="new-initiative-fields" class="space-y-4">
                    <label for="product-service" class="block text-gray-700 text-sm font-bold mb-2">Product or Service:</label>
                    <input type="text" id="product-service" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                    <textarea id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                              placeholder="Describe the product or service you'd like to bargain for, including any specific requirements or desired features."></textarea>
                    <label for="target-participants" class="block text-gray-700 text-sm font-bold mb-2">Target Number of Participants:</label>
                    <input type="number" id="target-participants" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="10" min="10">
                </div>
                <div id="join-initiative-fields" class="space-y-4 hidden">
                    <label for="existing-initiative" class="block text-gray-700 text-sm font-bold mb-2">Select Initiative:</label>
                    <select id="existing-initiative" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="" disabled selected>-- Select an Initiative --</option>
                        </select>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
                <div id="form-submission-confirmation" class="mt-4 p-4 bg-green-100 rounded-md border border-green-400 text-green-700 hidden">
                    <p>Thank you for your submission! We will contact you with updates.</p>
                </div>
                 <div id="form-submission-error" class="mt-4 p-4 bg-red-100 rounded-md border border-red-400 text-red-700 hidden">
                    <p>There was an error submitting your form. Please try again.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const initiativeTypeSelect = document.getElementById('initiative-type');
    const newInitiativeFields = document.getElementById('new-initiative-fields');
    const joinInitiativeFields = document.getElementById('join-initiative-fields');
    const collectiveBargainingForm = document.getElementById('collective-bargaining-form');
    const existingInitiativeSelect = document.getElementById('existing-initiative');
    const formSubmissionConfirmation = document.getElementById('form-submission-confirmation');
    const formSubmissionError = document.getElementById('form-submission-error');

    // Simulate fetching existing initiatives (replace with actual data retrieval)
    const existingInitiatives = [
        { id: 'solar-panels', name: 'Solar Panels & Installation' },
        { id: 'internet-service', name: 'High-Speed Internet Service' },
        { id: 'home-security', name: 'Community Home Security System' },
        { id: 'electric-vehicles', name: 'Electric Vehicle Group Buy' },
        { id: 'bulk-groceries', name: 'Bulk Organic Groceries' }
    ];

    function populateExistingInitiatives() {
        existingInitiativeSelect.innerHTML = '<option value="" disabled selected>-- Select an Initiative --</option>';
        existingInitiatives.forEach(initiative => {
            const option = document.createElement('option');
            option.value = initiative.id;
            option.textContent = initiative.name;
            existingInitiativeSelect.appendChild(option);
        });
    }

    initiativeTypeSelect.addEventListener('change', () => {
        if (initiativeTypeSelect.value === 'new') {
            newInitiativeFields.classList.remove('hidden');
            joinInitiativeFields.classList.add('hidden');
        } else {
            newInitiativeFields.classList.add('hidden');
            joinInitiativeFields.classList.remove('hidden');
            populateExistingInitiatives();
        }
    });

    collectiveBargainingForm.addEventListener('submit', (event) => {
        event.preventDefault();

        // In a real application, you would send the form data to a server.
        // For this example, we'll simulate a successful submission.

        setTimeout(() => {
            const success = Math.random() < 0.8;
            if (success) {
                formSubmissionConfirmation.classList.remove('hidden');
                collectiveBargainingForm.reset();
                 window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                formSubmissionError.classList.remove('hidden');
            }
        }, 2000);
    });
});
</script>

</div>

{{{footer}}}