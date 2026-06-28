import FingerprintJS from '@fingerprintjs/fingerprintjs';

let visitorIdPromise = null;

export function getVisitorId() {
    if (! visitorIdPromise) {
        visitorIdPromise = FingerprintJS.load()
            .then((agent) => agent.get())
            .then((result) => result.visitorId);
    }

    return visitorIdPromise;
}

window.getVisitorId = getVisitorId;
