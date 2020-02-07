class Observable {
    constructor() {
        this.observers = [];
    }

    // Add an observer to this.observers.
    addObserver(observer) {
        this.observers.push(observer);
    }

    // Remove an observer from this.observers.
    removeObserver(observer) {
        console.log(observer)
        const removeIndex = this.observers.findIndex(obs => {
            return observer === obs;
        });
        console.log(removeIndex)

        if (removeIndex !== -1) {
            console.log(removeIndex)
            this.observers.splice(removeIndex,1);
            console.log(this.observers)
        }
    }

    // Loops over this.observers and calls the update method on each observer.
    // The state object will call this method everytime it is updated.
    notify(...data) {
        if (this.observers.length > 0) {
            console.log('fdfdg')
            this.observers.forEach(observer => observer.update(data));
        }
    }
}

export { Observable }