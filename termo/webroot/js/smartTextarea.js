/* SEDUC DPID - Manuel Afonso 47061 em 10/08/2026 */
class SmartTextarea {
    
    constructor(textarea) {
        this.textarea = textarea;
        this.expanded = true;
        this.currentHeight = 0;
        this.create();
        this.events();
        this.render();
    }
    
    create() {
        const wrapper = document.createElement('div');
        wrapper.className = 'st-wrapper';
        this.textarea.parentNode.insertBefore(wrapper, this.textarea);
        wrapper.appendChild(this.textarea);
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'st-toggle';
        button.style.display = 'none';
        const icon = document.createElement('i');
        icon.className = 'bi bi-chevron-up';
        button.appendChild(icon);
        const preview = document.createElement('div');
        preview.className = 'st-preview';
        wrapper.appendChild(button);
        wrapper.appendChild(preview);
        this.wrapper = wrapper;
        this.button = button;
        this.icon = icon;
        this.preview = preview;
    }
    
    events() {
        ['input', 'change', 'paste'].forEach(event => {
            this.textarea.addEventListener(event, () => {
                this.resize();
                this.updateButton();
            });
        });
        this.button.addEventListener('click', () => {
            if (this.expanded) this.collapse(); else this.expand();
        });
    }
    
    render() {
        if (this.textarea.value.trim() === '') {
            this.expanded = true;
            this.textarea.style.height = this.getMinHeight() + 'px';
            this.currentHeight = this.getMinHeight();
        } else this.resize();
        this.updateButton();
    }
    
    resize() {
        if (!this.expanded) return;
        const minHeight = this.getMinHeight();
        this.textarea.style.transition = 'none';
        this.textarea.style.height = '0px';
        const contentHeight = this.textarea.scrollHeight;
        const newHeight = Math.max(contentHeight, minHeight);
        this.textarea.style.height = newHeight + 'px';
        this.currentHeight = newHeight;
        this.forceReflow();
        this.textarea.style.transition = '';
    }
    
    updateButton() {
        const hasText = this.textarea.value.trim() !== '';
        const multipleLines = this.hasMultipleLines();
        this.button.style.display = hasText && multipleLines ? 'flex' : 'none';
    }
    
    expand() {
        if (this.expanded) return;
        this.expanded = true;
        const minHeight = this.getMinHeight();
        this.textarea.style.transition = 'none';
        this.textarea.style.height = '0px';
        const contentHeight = this.textarea.scrollHeight;
        const newHeight = Math.max(contentHeight, minHeight);
        this.textarea.style.height = minHeight + 'px';
        this.textarea.scrollTop = 0;
        this.forceReflow();
        requestAnimationFrame(() => {
            this.textarea.style.transition = 'height 0.2s ease';
            this.textarea.style.height = newHeight + 'px';
            this.textarea.scrollTop = 0;
            this.currentHeight = newHeight;
        });
        this.icon.className = 'bi bi-chevron-up';
        this.updateButton();
        this.textarea.addEventListener('transitionend',() => {
                this.textarea.scrollTop = 0;
                this.textarea.focus();
                const end = this.textarea.value.length;
                this.textarea.setSelectionRange(end, end);
                this.textarea.scrollTop = 0;
            },
            { once: true }
        );
    }
    
    collapse() {
        if (!this.expanded) return;
        this.expanded = false;
        const minHeight = this.getMinHeight();
        const currentHeight = this.forceReflow();
        this.textarea.style.transition = 'none';
        this.textarea.style.height = currentHeight + 'px';
        this.forceReflow();
        requestAnimationFrame(() => {
            this.textarea.style.transition = 'height 0.2s ease';
            this.textarea.style.height = minHeight + 'px';
            this.currentHeight = minHeight;
        });
        this.icon.className = 'bi bi-chevron-down';
        this.textarea.blur();
        this.updateButton();
    }
    
    getMinHeight() {
        const style = getComputedStyle(this.textarea);
        const value = property => {
            const result = parseFloat(style[property]);
            return isNaN(result) ? 0 : result;
        };
        return Math.ceil(
            value('lineHeight') +
            value('paddingTop') +
            value('paddingBottom') +
            value('borderTopWidth') +
            value('borderBottomWidth')
        );
    }
    
    hasMultipleLines() {
        const oldHeight = this.textarea.style.height;
        this.textarea.style.height = 'auto';
        const result = this.textarea.scrollHeight > this.getMinHeight() + 2;
        this.textarea.style.height = oldHeight;
        return result;
    }
    
    forceReflow() { return this.textarea.offsetHeight; }
}
    
SmartTextarea.init = function(selector) {
    document.querySelectorAll(selector).forEach(textarea => {
        if (!textarea.dataset.smart) {
            textarea.dataset.smart = 'true';
            new SmartTextarea(textarea);
        }
    });
};

document.addEventListener('DOMContentLoaded', () => { SmartTextarea.init('.smart-textarea'); });
