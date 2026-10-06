import Alpine from 'alpinejs';

const money = (value) =>
    'CI$' + (Number(value) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

/**
 * E-box air freight estimator.
 * Chargeable weight is the greater of actual and dimensional weight (L×W×H ÷ 166),
 * rounded up to the next pound. First pound is a flat fee, each further pound is charged per lb.
 */
Alpine.data('eboxCalculator', (config) => ({
    weight: '',
    length: '',
    width: '',
    height: '',
    plan: 'lite',
    plans: config.plans,
    money,
    get dimensional() {
        const volume = Number(this.length) * Number(this.width) * Number(this.height);
        return volume > 0 ? volume / config.dim_divisor : 0;
    },
    get chargeable() {
        const greater = Math.max(Number(this.weight) || 0, this.dimensional);
        return greater > 0 ? Math.max(1, Math.ceil(greater - 1e-9)) : 0;
    },
    get usesDimensional() {
        return this.dimensional > (Number(this.weight) || 0);
    },
    cost(plan) {
        if (!this.chargeable) return 0;
        const p = this.plans[plan];
        return p.first_lb + (this.chargeable - 1) * p.per_lb;
    },
    get saving() {
        return this.cost('lite') - this.cost('pro');
    },
}));

/**
 * Ocean freight estimator. Cubic feet = L×W×H ÷ 1728 summed across packages in a consolidation.
 */
Alpine.data('oceanCalculator', (config) => ({
    packages: [{ length: '', width: '', height: '', qty: 1 }],
    money,
    add() {
        this.packages.push({ length: '', width: '', height: '', qty: 1 });
    },
    remove(index) {
        this.packages.splice(index, 1);
    },
    cubicFeet(p) {
        const volume = Number(p.length) * Number(p.width) * Number(p.height) * (Number(p.qty) || 1);
        return volume > 0 ? volume / config.divisor : 0;
    },
    get total() {
        return this.packages.reduce((sum, p) => sum + this.cubicFeet(p), 0);
    },
    get breakdown() {
        const cf = this.total;
        if (!cf) return [];
        const lines = [{ label: `Up to ${config.flat_cf} c.f. flat rate`, amount: config.flat_rate }];
        const mid = Math.max(0, Math.min(cf, config.mid_cf) - config.flat_cf);
        const high = Math.max(0, cf - config.mid_cf);
        if (mid > 0) lines.push({ label: `${mid.toFixed(1)} c.f. × ${money(config.mid_rate)}`, amount: mid * config.mid_rate });
        if (high > 0) lines.push({ label: `${high.toFixed(1)} c.f. × ${money(config.high_rate)}`, amount: high * config.high_rate });
        return lines;
    },
    get cost() {
        return this.breakdown.reduce((sum, line) => sum + line.amount, 0);
    },
}));

/** Physical mailbox application: live annual total. */
Alpine.data('mailboxApplication', (config, initial) => ({
    plan: initial.plan,
    extra: initial.extra,
    applicant: initial.applicant,
    money,
    get planPrice() {
        return config.plans[this.plan]?.price ?? 0;
    },
    get extraCost() {
        return (Number(this.extra) || 0) * config.extra_recipient;
    },
    get total() {
        return this.planPrice + this.extraCost + config.key_deposit;
    },
}));

/** Multi-step form (print quote). Validates the visible step with native constraints before moving on. */
Alpine.data('stepper', (steps, start = 0) => ({
    step: start,
    steps,
    next() {
        const fields = this.$root.querySelectorAll(`[data-step="${this.step}"] :is(input, select, textarea)`);
        for (const field of fields) {
            if (!field.checkValidity()) {
                field.reportValidity();
                return;
            }
        }
        this.go(this.step + 1);
    },
    go(index) {
        this.step = Math.max(0, Math.min(steps - 1, index));
        this.$nextTick(() => this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    },
}));

/** Count-up for hero / stat numbers when scrolled into view. */
Alpine.data('countUp', (target, duration = 1400) => ({
    value: 0,
    init() {
        if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.value = target;
            return;
        }
        const observer = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) return;
            observer.disconnect();
            const start = performance.now();
            const tick = (now) => {
                const progress = Math.min(1, (now - start) / duration);
                this.value = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                if (progress < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
        observer.observe(this.$el);
    },
}));

/** File input with a friendly filename readout. */
Alpine.data('fileField', () => ({
    name: '',
    pick(event) {
        this.name = event.target.files[0]?.name ?? '';
    },
}));

window.Alpine = Alpine;
Alpine.start();

// Scroll reveal
document.documentElement.classList.add('js');
const reveal = new IntersectionObserver(
    (entries) => {
        for (const entry of entries) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                reveal.unobserve(entry.target);
            }
        }
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
);
document.querySelectorAll('[data-reveal]').forEach((el) => reveal.observe(el));
