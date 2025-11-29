import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['item'];

    connect() {
        // Simple staggered reveal on load
        this.itemTargets.forEach((element, index) => {
            setTimeout(() => {
                element.classList.add('is-visible');
            }, index * 200); // 200ms delay between each item
        });
    }
}
