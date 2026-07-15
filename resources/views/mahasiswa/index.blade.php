@extends('layouts.app')

@section('content')
<div
    x-data="{
        // hero + search
        searchQuery: '',
        searchResults: @js($faqs->flatten(1)->values()),
        searchLoading: false,
        activeCategory: null,
        openFaqId: null,

        // chat widget
        chatOpen: false,
        chatMessages: [],
        chatInput: '',
        chatFile: null,
        chatSending: false,
        chatLoading: false,
        unreadCount: 0,
        adminOnline: @js($adminOnline),

        // ticket form
        ticketForm: { name: '', email: '', subject: '', category: '', message: '' },
        ticketErrors: {},
        ticketSending: false,
        ticketSuccess: @js(session('ticket_success')),
        ticketFile: null,

        runSearch() {
            this.searchLoading = true;
            fetch(`{{ route('help-center.search-faq') }}?q=${encodeURIComponent(this.searchQuery)}${this.activeCategory ? '&category='+this.activeCategory : ''}`)
                .then(r => r.json())
                .then(data => { this.searchResults = data.results; this.searchLoading = false; });
        },
        setCategory(key) {
            this.activeCategory = (this.activeCategory === key) ? null : key;
            this.runSearch();
        },
        submitTicket() {
            this.ticketSending = true;
            this.ticketErrors = {};
            const fd = new FormData();
            Object.entries(this.ticketForm).forEach(([k,v]) => fd.append(k, v));
            if (this.ticketFile) fd.append('attachment', this.ticketFile);

            fetch(`{{ route('help-center.store-ticket') }}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                body: fd,
            })
            .then(async r => {
                const data = await r.json();
                if (!r.ok) { this.ticketErrors = data.errors || {}; return; }
                this.ticketSuccess = data.message;
                this.ticketForm = { name:'', email:'', subject:'', category:'', message:'' };
                this.ticketFile = null;
            })
            .finally(() => this.ticketSending = false);
        },
        sendChatMessage() {
            if (!this.chatInput.trim() && !this.chatFile) return;
            this.chatSending = true;
            // TODO: hit endpoint chat kamu (belum ada di controller yang di-upload)
        }
    }"
>
    @include('help-center._hero')
    @include('help-center._categories', ['categories' => $categories])
    @include('help-center._faq')
    @include('help-center._email-form', ['categories' => $categories])
    @include('help-center._chat-widget')
</div>
@endsection