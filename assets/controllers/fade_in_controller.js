import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {
        this.element.style.opacity = 0;
        this.element.style.transform = 'translateY(20px)';
        this.element.style.transition = 'opacity 1s ease-out, transform 1s ease-out';

        setTimeout(() => {
            this.element.style.opacity = 1;
            this.element.style.transform = 'translateY(0)';
        }, 1000);
    }
}
