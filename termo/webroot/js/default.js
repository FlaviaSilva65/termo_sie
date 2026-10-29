import "./bootstrap.bundle.js"
import "./modules/string.js"

const credits = 'Desenvolvido por Anderson Santana'

const debug = (...pars) => console.trace(...pars)

const datalistBuild = (select) => {
    if (/select/.test(select.type)) {
        const container = select.parentNode
        const count = document.querySelectorAll('datalist').length
        const datalist = `<datalist id="list-${select.id}-${count}">${select.innerHTML}</datalist>`.toElement()
        const input = `<input data-datalist list="list-${select.id}-${count}" id="${select.id}" name="${select.name}" class="form-control" autocomplete="off" placeholder="Escolha a Opção">`.toElement()
        container.appendChild(datalist)
        container.appendChild(input)
        input.addEventListener('change', event => datalistSelect(input))
        input.disabled = select.disabled
        input.readOnly = select.disabled
        input.required = select.required
        select.classList.add('d-none')
        datalistSelect(input, true)
    }
}

const datalistSelect = (input, asDefault) => {
    const acceptAny = input.accept == 'any'
    const datalist = input.parentNode.querySelector('datalist')
    const hidden = input.parentNode.querySelector('select, input[type=hidden]')
    const options = datalist.querySelectorAll('option')
    const selected = Array.prototype.find.call(options, option => input.value ? option.innerText == input.value || option.value == input.value : option.selected)
    if (selected && selected.selected) selected.selected = false
    if (acceptAny) return
    hidden.value = selected ? selected.value : null
    input.value = selected ? selected.innerText : input.value
    hidden.defaultValue = asDefault && selected ? selected.value : hidden.defaultValue
    input.defaultValue = asDefault && selected ? selected.innerText : input.defaultValue
}

const userTheme = theme => {
    const isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
    return isDark ? 'dark' : 'light'
}

const getThemeLocal = theme => {
    return localStorage.getItem('theme')
}

const setTheme = theme => {
    localStorage.setItem('theme', theme)
    document.body.setAttribute('data-bs-theme', theme)
}

const setPassword = button => {
    let input = button.parentNode.parentNode.querySelector('input')
    var icon = button
    input.type = input.type == 'password' ? 'text' : 'password'
    icon.classList[input.type == 'password' ? 'add' : 'remove']('bi-eye-slash')
    icon.classList[input.type != 'password' ? 'add' : 'remove']('bi-eye')
}

const checkTheme = document.querySelector('#switch-theme')

const currentTheme = getThemeLocal()

const dataSelects = document.querySelectorAll('[data-dataselect]')

const iconPasswords = document.querySelectorAll('.icon-password')

if (checkTheme)
    checkTheme.onchange = event => setTheme(checkTheme.checked ? 'dark' : 'light')

if (checkTheme && getThemeLocal() == 'dark')
    checkTheme.click()

if (currentTheme)
    setTheme(currentTheme)

if (dataSelects)
    dataSelects.forEach(dataSelect => datalistBuild(dataSelect, true))

if (iconPasswords)
    iconPasswords.forEach(iconPassword => iconPassword.onclick = event => setPassword(iconPassword))

console.info(credits)

window.addEventListener('load', event => {
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]')
    popovers.forEach(popover => new bootstrap.Popover(popover))
    const toasts = document.querySelectorAll('.toast')
    toasts.forEach(toast => new bootstrap.Toast(toast).show())
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip))
})