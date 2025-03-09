<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 dark:bg-gray-800 dark:text-white">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold mb-4 sm:mb-6">
                        <span class="text-blue-600 dark:text-blue-400">Proposals</span>
                    </h2>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('proposals.index') }}"
                        class="mb-6 flex flex-col sm:flex-row gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name, email, or purpose..."
                            class="w-full sm:w-1/3 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select name="admin_status"
                            class="w-full sm:w-1/4 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Admin Status</option>
                            <option value="approved" {{ request('admin_status') === 'approved' ? 'selected' : '' }}>
                                Approved</option>
                            <option value="pending" {{ request('admin_status') === 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="rejected" {{ request('admin_status') === 'rejected' ? 'selected' : '' }}>
                                Rejected</option>
                        </select>
                        <select name="status"
                            class="w-full sm:w-1/4 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All AI Analysis</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved</option>
                            <option value="flagged" {{ request('status') === 'flagged' ? 'selected' : '' }}>Flagged
                            </option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error
                            </option>
                        </select>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Filter
                        </button>
                    </form>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    <th
                                        class="hidden px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        File</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Vendor Name</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Email</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Date Created</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Purpose</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        AI Analysis</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Admin Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Performed By</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proposals as $proposal)
                                    <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                        <td class="hidden px-4 py-3 text-sm">
                                            {{ basename($proposal->documentation_path) }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->user->name }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->user->email }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->created_at->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->purpose }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $aiStatusClasses = match ($proposal->status) {
                                                    'approved' => 'bg-green-100 text-green-600',
                                                    'flagged' => 'bg-orange-100 text-orange-600',
                                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                                    'error' => 'bg-red-100 text-red-600',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $aiStatusClasses }}">
                                                {{ ucfirst($proposal->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $adminStatusClasses = match ($proposal->admin_status ?? 'pending') {
                                                    'approved' => 'bg-green-200 text-green-800',
                                                    'rejected' => 'bg-red-200 text-red-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $adminStatusClasses }}">
                                                {{ ucfirst($proposal->admin_status ?? 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->actioned_by ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <button data-id="{{ $proposal->id }}"
                                                class="viewProposal bg-blue-500 text-white px-3 py-1 rounded-md text-xs">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9"
                                            class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No proposals found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $proposals->firstItem() }} to {{ $proposals->lastItem() }} of
                            {{ $proposals->total() }} proposals
                        </div>
                        <div class="flex gap-2">
                            @if ($proposals->onFirstPage())
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $proposals->previousPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Previous</a>
                            @endif
                            @foreach ($proposals->links()->elements[0] as $page => $url)
                                @if ($page == $proposals->currentPage())
                                    <span
                                        class="px-3 py-1 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if ($proposals->hasMorePages())
                                <a href="{{ $proposals->nextPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Next</a>
                            @else
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Proposal Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Proposal Details</h2>
                    <div class="flex items-center gap-2">
                        <button id="viewFileButton"
                            class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700 transition">
                            View File
                        </button>
                        <button id="closeViewModal" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                    </div>
                </div>
                <div id="proposalDetails" class="text-sm text-gray-700">
                    <table class="w-full border-collapse">
                        <tbody>
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button id="cancelView"
                        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Close
                    </button>
                    <button id="declineProposal"
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                        Decline Proposal
                    </button>
                    <button id="approveProposal"
                        class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 hidden">
                        Approve Proposal
                    </button>
                    <span id="statusIndicator" class="text-sm font-medium text-gray-600 hidden"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Confirm Proposal Approval</h2>
                    <button id="closeApproveConfirm" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                </div>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to approve this proposal?</p>
                <textarea id="approveNotes" class="w-full border p-2 rounded mb-4" rows="3"
                    placeholder="Enter approval notes (optional)"></textarea>
                <div class="flex justify-end gap-3">
                    <button id="cancelApproveConfirm"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button id="confirmApprove"
                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Approve Proposal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Decline Confirmation Modal -->
    <div id="declineConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Confirm Proposal Rejection</h2>
                    <button id="closeDeclineConfirm" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                </div>
                <p class="text-sm text-gray-700 mb-4">Are you sure you want to reject this proposal?</p>
                <textarea id="declineNotes" class="w-full border p-2 rounded mb-4" rows="3"
                    placeholder="Enter rejection reason (optional)"></textarea>
                <div class="flex justify-end gap-3">
                    <button id="cancelDeclineConfirm"
                        class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button id="confirmDecline"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                        Reject Proposal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewModal = document.getElementById("viewModal");
            const closeViewModal = document.getElementById("closeViewModal");
            const cancelView = document.getElementById("cancelView");
            const approveProposal = document.getElementById("approveProposal");
            const declineProposal = document.getElementById("declineProposal");
            const viewFileButton = document.getElementById("viewFileButton");
            const proposalDetails = document.getElementById("proposalDetails").querySelector("tbody");
            const statusIndicator = document.getElementById("statusIndicator");

            const approveConfirmModal = document.getElementById("approveConfirmModal");
            const closeApproveConfirm = document.getElementById("closeApproveConfirm");
            const cancelApproveConfirm = document.getElementById("cancelApproveConfirm");
            const confirmApprove = document.getElementById("confirmApprove");

            const declineConfirmModal = document.getElementById("declineConfirmModal");
            const closeDeclineConfirm = document.getElementById("closeDeclineConfirm");
            const cancelDeclineConfirm = document.getElementById("cancelDeclineConfirm");
            const confirmDecline = document.getElementById("confirmDecline");

            let currentProposalId = null;

            document.querySelectorAll(".viewProposal").forEach(button => {
                button.addEventListener("click", function() {
                    currentProposalId = this.getAttribute("data-id");
                    const proposals = @json($proposals->items());
                    const proposal = proposals.find(p => p.id == parseInt(currentProposalId));

                    if (proposal) {
                        proposalDetails.innerHTML = `
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Vendor Name</td>
                                <td class="py-2 px-4">${proposal.user.name}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Email</td>
                                <td class="py-2 px-4">${proposal.user.email}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Date Created</td>
                                <td class="py-2 px-4">${new Date(proposal.created_at).toLocaleDateString()}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Purpose</td>
                                <td class="py-2 px-4">${proposal.purpose}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Performed By</td>
                                <td class="py-2 px-4">${proposal.actioned_by || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">AI Analysis</td>
                                <td class="py-2 px-4">${(proposal.status || 'pending').charAt(0).toUpperCase() + (proposal.status || 'pending').slice(1)}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Admin Status</td>
                                <td class="py-2 px-4">${proposal.admin_status ? proposal.admin_status.charAt(0).toUpperCase() + proposal.admin_status.slice(1) : 'Pending'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Notes</td>
                                <td class="py-2 px-4">${proposal.fraud_notes || 'N/A'}</td>
                            </tr>
                        `;

                        const adminStatus = proposal.admin_status ? proposal.admin_status
                            .toLowerCase() : 'pending';
                        if (adminStatus === 'approved') {
                            approveProposal.classList.add("hidden");
                            declineProposal.classList.add("hidden");
                            statusIndicator.classList.remove("hidden");
                            statusIndicator.textContent = "Proposal Approved";
                            statusIndicator.classList.remove("text-red-600");
                            statusIndicator.classList.add("text-green-600");
                        } else if (adminStatus === 'rejected') {
                            approveProposal.classList.add("hidden");
                            declineProposal.classList.add("hidden");
                            statusIndicator.classList.remove("hidden");
                            statusIndicator.textContent = "Proposal Rejected";
                            statusIndicator.classList.remove("text-green-600");
                            statusIndicator.classList.add("text-red-600");
                        } else {
                            approveProposal.classList.remove("hidden");
                            declineProposal.classList.remove("hidden");
                            statusIndicator.classList.add("hidden");
                        }

                        viewModal.classList.remove("hidden");
                    } else {
                        console.error("Proposal not found for ID:", currentProposalId);
                    }
                });
            });

            viewFileButton.addEventListener("click", () => {
                if (currentProposalId) {
                    const fileUrl = "{{ route('proposals.preview', ':id') }}".replace(':id',
                        currentProposalId);
                    window.open(fileUrl, '_blank');
                } else {
                    console.error("No proposal ID available for preview.");
                }
            });

            function closeViewModalFn() {
                viewModal.classList.add("hidden");
                currentProposalId = null;
            }

            closeViewModal.addEventListener("click", closeViewModalFn);
            cancelView.addEventListener("click", closeViewModalFn);
            viewModal.addEventListener("click", (e) => {
                if (e.target === viewModal) {
                    closeViewModalFn();
                }
            });

            function closeApproveConfirmFn() {
                approveConfirmModal.classList.add("hidden");
                document.getElementById("approveNotes").value = "";
            }

            function closeDeclineConfirmFn() {
                declineConfirmModal.classList.add("hidden");
                document.getElementById("declineNotes").value = "";
            }

            closeApproveConfirm.addEventListener("click", closeApproveConfirmFn);
            cancelApproveConfirm.addEventListener("click", closeApproveConfirmFn);
            approveConfirmModal.addEventListener("click", (e) => {
                if (e.target === approveConfirmModal) {
                    closeApproveConfirmFn();
                }
            });

            closeDeclineConfirm.addEventListener("click", closeDeclineConfirmFn);
            cancelDeclineConfirm.addEventListener("click", closeDeclineConfirmFn);
            declineConfirmModal.addEventListener("click", (e) => {
                if (e.target === declineConfirmModal) {
                    closeDeclineConfirmFn();
                }
            });

            approveProposal.addEventListener("click", () => {
                approveConfirmModal.classList.remove("hidden");
            });

            declineProposal.addEventListener("click", () => {
                declineConfirmModal.classList.remove("hidden");
            });

            confirmApprove.addEventListener("click", () => {
                if (currentProposalId) {
                    const notes = document.getElementById("approveNotes").value;
                    fetch(`/proposals/${currentProposalId}/approve`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                notes: notes
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeApproveConfirmFn();
                                closeViewModalFn();
                                location.reload();
                            }
                        })
                        .catch(error => console.error("Approve error:", error));
                }
            });

            confirmDecline.addEventListener("click", () => {
                if (currentProposalId) {
                    const notes = document.getElementById("declineNotes").value;
                    fetch(`/proposals/${currentProposalId}/decline`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                notes: notes
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeDeclineConfirmFn();
                                closeViewModalFn();
                                location.reload();
                            }
                        })
                        .catch(error => console.error("Decline error:", error));
                }
            });
        });
    </script>
</x-app-layout>
