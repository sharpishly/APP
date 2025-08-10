{{{header}}}


<!-- Money Plus Group -->
<div class="layout-item">
 <section id="debt-management" class="py-12">
 <h1>{{{h1}}}</h1>
 <h2>{{{h2}}}</h2>
     <p class="mb-4">
        We understand that managing debt can be challenging. This service provides a way to submit your debt information
        for assessment and to explore potential solutions. Please fill out the form below to help us understand your financial situation.
    </p>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-blue-600 mb-4">Debtor Information</h3>
        <form id="debt-management-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name / Business Name:</label>
                    <input type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="dob" class="block text-gray-700 text-sm font-bold mb-2">Date of Birth (Individuals):</label>
                    <input type="date" id="dob" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                    <input type="text" id="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="postcode" class="block text-gray-700 text-sm font-bold mb-2">Postcode:</label>
                    <input type="text" id="postcode" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                    <input type="tel" id="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>
            <div>
                <label for="debtor-type" class="block text-gray-700 text-sm font-bold mb-2">Debtor Type:</label>
                <select id="debtor-type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="individual">Individual</option>
                    <option value="business">Business</option>
                </select>
            </div>

            <h3 class="text-xl font-semibold text-blue-600 mb-4">Debt Details</h3>
            <div id="creditor-list-container">
                <div class="creditor-entry border rounded-md p-4 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="creditor-name-1" class="block text-gray-700 text-sm font-bold mb-2">Creditor Name:</label>
                            <input type="text" id="creditor-name-1" name="creditor-name[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                        <div>
                            <label for="creditor-amount-1" class="block text-gray-700 text-sm font-bold mb-2">Amount Owed (£):</label>
                            <input type="number" id="creditor-amount-1" name="creditor-amount[]" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>
                     <div>
                        <label for="debt-type-1" class="block text-gray-700 text-sm font-bold mb-2">Debt Type:</label>
                        <select id="debt-type-1" name="debt-type[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="credit-card">Credit Card</option>
                            <option value="loan">Loan</option>
                            <option value="mortgage">Mortgage Arrears</option>
                            <option value="utilities">Utilities</option>
                            <option value="business-loan">Business Loan</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
            <button id="add-creditor" type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add Creditor</button>

            <div>
                <label for="total-income" class="block text-gray-700 text-sm font-bold mb-2">Total Monthly Income (£):</label>
                <input type="number" id="total-income" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div>
                <label for="total-expenses" class="block text-gray-700 text-sm font-bold mb-2">Total Monthly Expenses (£):</label>
                <input type="number" id="total-expenses" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label for="debt-solution" class="block text-gray-700 text-sm font-bold mb-2">Preferred Debt Solution (if known):</label>
                <select id="debt-solution" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="none">None</option>
                    <option value="dmp">Debt Management Plan</option>
                    <option value="iva">Individual Voluntary Arrangement (IVA)</option>
                    <option value="bankruptcy">Bankruptcy</option>
                    <option value="consolidation">Debt Consolidation Loan</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label for="additional-info" class="block text-gray-700 text-sm font-bold mb-2">Additional Information:</label>
                <textarea id="additional-info" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <h3 class="text-xl font-semibold text-blue-600 mb-4">Declaration</h3>
            <p class="mb-4">
                I declare that the information provided in this application is true and complete to the best of my knowledge.
            </p>
            <div>
                <label for="declaration" class="inline-flex items-center">
                    <input type="checkbox" id="declaration" class="form-checkbox h-5 w-5 text-blue-600 rounded" required>
                    <span class="ml-2 text-gray-700 text-sm">I agree to the above declaration.</span>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit Application</button>
            <div id="submission-confirmation" class="mt-4 p-4 bg-green-100 rounded-md border border-green-400 text-green-700 hidden">
                <p>Your application has been submitted successfully. We will contact you shortly to discuss your options.</p>
            </div>
            <div id="submission-error" class="mt-4 p-4 bg-red-100 rounded-md border border-red-400 text-red-700 hidden">
                <p>There was an error submitting your application. Please try again.</p>
            </div>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const debtManagementForm = document.getElementById('debt-management-form');
    const submissionConfirmation = document.getElementById('submission-confirmation');
    const submissionError = document.getElementById('submission-error');
    const addCreditorButton = document.getElementById('add-creditor');
    const creditorListContainer = document.getElementById('creditor-list-container');

    let creditorCount = 1;

    addCreditorButton.addEventListener('click', () => {
        creditorCount++;
        const newCreditorEntry = document.createElement('div');
        newCreditorEntry.className = 'creditor-entry border rounded-md p-4 mb-4';
        newCreditorEntry.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="creditor-name-${creditorCount}" class="block text-gray-700 text-sm font-bold mb-2">Creditor Name:</label>
                    <input type="text" id="creditor-name-${creditorCount}" name="creditor-name[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div>
                    <label for="creditor-amount-${creditorCount}" class="block text-gray-700 text-sm font-bold mb-2">Amount Owed (£):</label>
                    <input type="number" id="creditor-amount-${creditorCount}" name="creditor-amount[]" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>
            <div>
                <label for="debt-type-${creditorCount}" class="block text-gray-700 text-sm font-bold mb-2">Debt Type:</label>
                <select id="debt-type-${creditorCount}" name="debt-type[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="credit-card">Credit Card</option>
                    <option value="loan">Loan</option>
                    <option value="mortgage">Mortgage Arrears</option>
                    <option value="utilities">Utilities</option>
                     <option value="business-loan">Business Loan</option>
                    <option value="other">Other</option>
                </select>
            </div>
        `;
        creditorListContainer.appendChild(newCreditorEntry);
    });

    debtManagementForm.addEventListener('submit', (event) => {
        event.preventDefault();

        // In a real application, you would send this data to a server
        // using fetch or AJAX. For this example, we'll just simulate a submission.

        // Simulate a successful submission (replace with your actual submission logic)
        setTimeout(() => {
            const success = Math.random() < 0.8; // Simulate 80% success rate
            if (success) {
                submissionConfirmation.classList.remove('hidden');
                debtManagementForm.reset();
                creditorCount = 1;
                creditorListContainer.innerHTML = `
                    <div class="creditor-entry border rounded-md p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="creditor-name-1" class="block text-gray-700 text-sm font-bold mb-2">Creditor Name:</label>
                                <input type="text" id="creditor-name-1" name="creditor-name[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                            <div>
                                <label for="creditor-amount-1" class="block text-gray-700 text-sm font-bold mb-2">Amount Owed (£):</label>
                                <input type="number" id="creditor-amount-1" name="creditor-amount[]" min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </div>
                        </div>
                         <div>
                            <label for="debt-type-1" class="block text-gray-700 text-sm font-bold mb-2">Debt Type:</label>
                            <select id="debt-type-1" name="debt-type[]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="credit-card">Credit Card</option>
                                <option value="loan">Loan</option>
                                <option value="mortgage">Mortgage Arrears</option>
                                <option value="utilities">Utilities</option>
                                <option value="business-loan">Business Loan</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                `;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                submissionError.classList.remove('hidden');
            }
        }, 2000); // Simulate a 2-second delay
    });
});
</script>

 </div>
<!-- Money Plus Group -->


{{{footer}}}