@extends('pro.layout')

@section('title', 'Agenda')

@section('content')
<div class="agenda-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1e293b;">
                <i class="fas fa-calendar-check me-2" style="color: var(--pro-primary);"></i>Agenda & Planning
            </h4>
            <p class="text-muted mb-0 small">Suivez vos rendez-vous, échéances et interactions clients</p>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="eventFilter" style="width: auto; border-radius: 10px;">
                <option value="all">Tout afficher</option>
                <option value="quote">Devis uniquement</option>
                <option value="invoice">Factures uniquement</option>
                <option value="interaction">Interactions</option>
            </select>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="agenda-stat-card">
                <div class="agenda-stat-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <span class="agenda-stat-value">{{ $agendaStats['overdue_invoices'] }}</span>
                    <span class="agenda-stat-label">Factures en retard</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="agenda-stat-card">
                <div class="agenda-stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <span class="agenda-stat-value">{{ $agendaStats['upcoming_deadlines'] }}</span>
                    <span class="agenda-stat-label">Échéances cette semaine</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="agenda-stat-card">
                <div class="agenda-stat-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <span class="agenda-stat-value">{{ $agendaStats['active_quotes'] }}</span>
                    <span class="agenda-stat-label">Devis en attente</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="agenda-stat-card">
                <div class="agenda-stat-icon" style="background: rgba(34,197,94,0.1); color: #22c55e;">
                    <i class="fas fa-tasks"></i>
                </div>
                <div>
                    <span class="agenda-stat-value">{{ $agendaStats['today_tasks'] }}</span>
                    <span class="agenda-stat-label">Activités aujourd'hui</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Timeline --}}
        <div class="col-lg-8">
            <div class="agenda-card">
                <div class="agenda-card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fas fa-stream me-2 text-primary"></i>Fil d'activité</h6>
                    <span class="badge bg-light text-dark" id="eventCount">{{ count($events) }} événements</span>
                </div>
                <div class="agenda-card-body">
                    @if(count($events) === 0)
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-day" style="font-size: 3rem; color: #e2e8f0;"></i>
                            <p class="text-muted mt-3">Aucun événement à afficher pour le moment.</p>
                            <p class="text-muted small">Les devis acceptés, factures en attente et interactions clients apparaîtront ici.</p>
                        </div>
                    @else
                        <div class="timeline" id="eventsTimeline">
                            @php
                                $lastDate = null;
                            @endphp
                            @foreach($events as $event)
                                @php
                                    $eventDate = \Carbon\Carbon::parse($event['date']);
                                    $dateLabel = $eventDate->isToday() ? "Aujourd'hui" :
                                                ($eventDate->isYesterday() ? 'Hier' :
                                                ($eventDate->isTomorrow() ? 'Demain' :
                                                $eventDate->translatedFormat('l d F Y')));
                                    $showDateSep = ($lastDate !== $dateLabel);
                                    $lastDate = $dateLabel;
                                @endphp

                                @if($showDateSep)
                                    <div class="timeline-date-separator">
                                        <span>{{ $dateLabel }}</span>
                                    </div>
                                @endif

                                <div class="timeline-item" data-type="{{ $event['type'] }}">
                                    <div class="timeline-dot" style="background: {{ $event['color'] }};"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <span class="timeline-badge" style="background: {{ $event['color'] }}15; color: {{ $event['color'] }};">
                                                    @if($event['type'] === 'quote')
                                                        <i class="fas fa-file-alt me-1"></i>Devis
                                                    @elseif($event['type'] === 'invoice')
                                                        <i class="fas fa-file-invoice-dollar me-1"></i>Facture
                                                    @else
                                                        <i class="fas fa-handshake me-1"></i>Interaction
                                                    @endif
                                                </span>
                                                <h6 class="timeline-title mt-2 mb-1">{{ $event['title'] }}</h6>
                                                <span class="timeline-client">
                                                    <i class="fas fa-user me-1"></i>{{ $event['client'] }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <span class="timeline-time">
                                                    <i class="far fa-clock me-1"></i>{{ $eventDate->translatedFormat('H:i') }}
                                                </span>
                                                @if(isset($event['amount']) && $event['amount'])
                                                    <div class="timeline-amount mt-1">
                                                        {{ number_format($event['amount'], 2, ',', ' ') }} €
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Status badge --}}
                                        <div class="mt-2">
                                            @php
                                                $statusConfig = [
                                                    'pending' => ['label' => 'En attente', 'class' => 'bg-warning text-dark'],
                                                    'accepted' => ['label' => 'Accepté', 'class' => 'bg-success'],
                                                    'sent' => ['label' => 'Envoyée', 'class' => 'bg-info'],
                                                    'paid' => ['label' => 'Payée', 'class' => 'bg-success'],
                                                    'rejected' => ['label' => 'Refusé', 'class' => 'bg-danger'],
                                                    'active' => ['label' => 'Actif', 'class' => 'bg-success'],
                                                    'inactive' => ['label' => 'Inactif', 'class' => 'bg-secondary'],
                                                ];
                                                $sc = $statusConfig[$event['status']] ?? ['label' => ucfirst($event['status']), 'class' => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $sc['class'] }}" style="font-size: .7rem;">{{ $sc['label'] }}</span>

                                            @if($event['type'] === 'invoice' && $eventDate->isPast())
                                                <span class="badge bg-danger" style="font-size: .7rem;">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Échue
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Sidebar --}}
        <div class="col-lg-4">
            {{-- Today Overview --}}
            <div class="agenda-card mb-3">
                <div class="agenda-card-header">
                    <h6 class="fw-bold mb-0"><i class="fas fa-sun me-2 text-warning"></i>Aujourd'hui</h6>
                </div>
                <div class="agenda-card-body">
                    <div class="today-date text-center mb-3">
                        <div class="today-day">{{ now()->translatedFormat('d') }}</div>
                        <div class="today-month">{{ now()->translatedFormat('F Y') }}</div>
                        <div class="today-weekday text-muted">{{ now()->translatedFormat('l') }}</div>
                    </div>
                    <div class="today-summary">
                        <div class="today-summary-item">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>{{ $agendaStats['today_tasks'] }} activité(s)</span>
                        </div>
                        <div class="today-summary-item">
                            <i class="fas fa-hourglass-half text-warning"></i>
                            <span>{{ $agendaStats['active_quotes'] }} devis en cours</span>
                        </div>
                        <div class="today-summary-item">
                            <i class="fas fa-bell text-danger"></i>
                            <span>{{ $agendaStats['overdue_invoices'] }} rappel(s)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="agenda-card mb-3">
                <div class="agenda-card-header">
                    <h6 class="fw-bold mb-0"><i class="fas fa-bolt me-2" style="color: #f59e0b;"></i>Actions rapides</h6>
                </div>
                <div class="agenda-card-body p-2">
                    <a href="{{ route('pro.quotes.create') }}" class="quick-action-btn">
                        <i class="fas fa-plus-circle text-primary"></i>
                        <span>Créer un devis</span>
                    </a>
                    <a href="{{ route('pro.invoices.create') }}" class="quick-action-btn">
                        <i class="fas fa-file-invoice text-success"></i>
                        <span>Créer une facture</span>
                    </a>
                    <a href="{{ route('pro.clients') }}" class="quick-action-btn">
                        <i class="fas fa-user-plus text-info"></i>
                        <span>Ajouter un client</span>
                    </a>
                    <a href="{{ route('pro.profile.edit') }}" class="quick-action-btn">
                        <i class="fas fa-user-edit text-secondary"></i>
                        <span>Modifier profil</span>
                    </a>
                </div>
            </div>

            {{-- Upcoming Deadlines --}}
            <div class="agenda-card">
                <div class="agenda-card-header">
                    <h6 class="fw-bold mb-0"><i class="fas fa-flag me-2 text-danger"></i>Prochaines échéances</h6>
                </div>
                <div class="agenda-card-body">
                    @php
                        $deadlines = $events->filter(function ($e) {
                            return $e['type'] === 'invoice' && \Carbon\Carbon::parse($e['date'])->isFuture();
                        })->take(5);
                    @endphp
                    @if($deadlines->count() === 0)
                        <p class="text-muted text-center small py-3 mb-0">Aucune échéance à venir</p>
                    @else
                        @foreach($deadlines as $dl)
                            <div class="deadline-item">
                                <div class="deadline-info">
                                    <span class="deadline-title">{{ $dl['title'] }}</span>
                                    <span class="deadline-client">{{ $dl['client'] }}</span>
                                </div>
                                <div class="deadline-date">
                                    {{ \Carbon\Carbon::parse($dl['date'])->translatedFormat('d M') }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.agenda-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    transition: transform .2s;
}
.agenda-stat-card:hover { transform: translateY(-2px); }
.agenda-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.agenda-stat-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    display: block;
    line-height: 1.2;
}
.agenda-stat-label {
    font-size: .72rem;
    color: #94a3b8;
}

.agenda-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    overflow: hidden;
}
.agenda-card-header {
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
}
.agenda-card-body {
    padding: 16px 20px;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 24px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e2e8f0;
}
.timeline-date-separator {
    position: relative;
    margin: 20px 0 12px -24px;
    text-align: left;
}
.timeline-date-separator span {
    background: #f1f5f9;
    color: #64748b;
    font-size: .75rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.timeline-item {
    position: relative;
    margin-bottom: 16px;
    animation: fadeInUp .3s ease-out;
}
.timeline-dot {
    position: absolute;
    left: -20px;
    top: 8px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}
.timeline-content {
    background: #f8fafc;
    border-radius: 10px;
    padding: 14px 16px;
    border: 1px solid #f1f5f9;
    transition: box-shadow .2s;
}
.timeline-content:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.timeline-badge {
    font-size: .68rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
}
.timeline-title {
    font-size: .88rem;
    font-weight: 600;
    color: #1e293b;
}
.timeline-client {
    font-size: .78rem;
    color: #64748b;
}
.timeline-time {
    font-size: .72rem;
    color: #94a3b8;
    white-space: nowrap;
}
.timeline-amount {
    font-size: .85rem;
    font-weight: 700;
    color: #1e293b;
}

/* Today */
.today-day {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--pro-primary, #6366f1);
    line-height: 1;
}
.today-month {
    font-size: .85rem;
    font-weight: 600;
    color: #1e293b;
    text-transform: capitalize;
}
.today-weekday {
    font-size: .75rem;
    text-transform: capitalize;
}
.today-summary-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    font-size: .82rem;
    color: #475569;
    border-bottom: 1px solid #f1f5f9;
}
.today-summary-item:last-child { border-bottom: none; }

/* Quick Actions */
.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: #475569;
    text-decoration: none;
    font-size: .82rem;
    transition: background .15s;
}
.quick-action-btn:hover {
    background: #f1f5f9;
    color: #1e293b;
}

/* Deadlines */
.deadline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}
.deadline-item:last-child { border-bottom: none; }
.deadline-title {
    font-size: .82rem;
    font-weight: 600;
    color: #1e293b;
    display: block;
}
.deadline-client {
    font-size: .72rem;
    color: #94a3b8;
}
.deadline-date {
    font-size: .75rem;
    font-weight: 600;
    color: #ef4444;
    white-space: nowrap;
    background: rgba(239,68,68,0.08);
    padding: 4px 10px;
    border-radius: 6px;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filter = document.getElementById('eventFilter');
    const timeline = document.getElementById('eventsTimeline');
    const countBadge = document.getElementById('eventCount');

    if (filter && timeline) {
        filter.addEventListener('change', function() {
            const type = this.value;
            const items = timeline.querySelectorAll('.timeline-item');
            let visible = 0;

            items.forEach(item => {
                if (type === 'all' || item.dataset.type === type) {
                    item.style.display = '';
                    visible++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (countBadge) {
                countBadge.textContent = visible + ' événement' + (visible > 1 ? 's' : '');
            }
        });
    }
});
</script>
@endsection
