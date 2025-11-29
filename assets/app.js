import './styles/app.scss';
import { Application } from '@hotwired/stimulus';

const app = Application.start();

// Manually register controllers
const controllers = import.meta.glob('./controllers/*_controller.js', { eager: true });

for (const [path, module] of Object.entries(controllers)) {
    const controllerName = path
        .replace(/^.\/controllers\//, '')
        .replace(/_controller\.js$/, '')
        .replace(/_/g, '-');
    
    app.register(controllerName, module.default);
}