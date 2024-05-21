const base_url = ""

class Helpers {

    static request(options) {
        let defaults = {
            method: 'GET',
            url: '',
            data: null,
            headers: {
                'Content-Type': 'application/json'
            },
            dataType: 'json',
            beforeSend: function (xhr) { },
            success: function (data) { },
            error: function (error) { }
        };

        let config = Object.assign({}, defaults, options);

        let requestOptions = {
            method: config.method,
            headers: config.headers
        };

        if (config.data) {
            if (config.headers['Content-Type'] === 'application/json') {
                requestOptions.body = JSON.stringify(config.data);
            } else {
                requestOptions.body = config.data;
            }
        }

        if (typeof config.beforeSend === 'function') {
            config.beforeSend(requestOptions);
        }

        fetch(config.url, requestOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Request Error ${response.statusText}`);
                }
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json') && config.dataType === 'json') {
                    return response.json();
                } else {
                    return response.text();
                }
            })
            .then(data => {
                config.success(data);
            })
            .catch(error => {
                config.error(error.message);
            });
    }

}

class Dom {

    static val(selector, newValue) {
        let element = this.sel(selector);

        if (!element) {
            console.warn(`Elemento não encontrado para o seletor '${selector}'`);
            return null;  // ou retorna um valor padrão 
        }

        if (newValue !== undefined) {
            element.value = newValue;
        }

        return element.value;
    }

    static sel(selector) {
        return document.querySelector(selector);
    }
    static selAll(selector) {
        return document.querySelectorAll(selector);
    }
    static selVal(selector) {
        let element = this.sel(selector);

        if (element && 'value' in element) {
            return element.value;
        } else {
            console.warn(`Elemento não encontrado ou não possui a propriedade 'value' para o seletor '${selector}'`);
            return null;
        }
    }
    static setHtml(selector, html) {
        let elements = this.selAll(selector);
        elements.forEach(function (element) {
            element.innerHTML = html;
        });
    }
    static addCSS(css) {
        const styleElement = document.createElement('style');
        styleElement.type = 'text/css';

        if (styleElement.styleSheet) {
            styleElement.styleSheet.cssText = css;
        } else {
            styleElement.appendChild(document.createTextNode(css));
        }

        document.head.appendChild(styleElement);
    }
    static setHtmlWithFadeIn(selector, html) {
        let elements = this.selAll(selector);

        this.addCSS(`
            .fade-in {
                opacity: 0;
                transition: opacity 0.5s ease-in-out;
            }
    
            .fade-in.active {
                opacity: 1;
            }
        `);

        elements.forEach(function (element) {
            element.classList.add('fade-in');

            setTimeout(function () {
                element.innerHTML = html;

                element.classList.add('active');

                setTimeout(function () {
                    element.classList.remove('fade-in', 'active');
                }, 500);
            }, 10);
        });
    }
    static html(selector, newHtml) {
        let elements = this.selAll(selector);

        if (!elements || elements.length === 0) {
            console.warn(`Elementos não encontrados para o seletor '${selector}'`);
            return null;
        }

        if (newHtml !== undefined) {
            elements.forEach(element => {
                element.innerHTML = newHtml;
            });
        }

        return elements[0].innerHTML;
    }

    static addClass(selector, className) {
        let elements = this.selAll(selector);

        if (!elements || elements.length === 0) {
            console.warn(`Elementos não encontrados para o seletor '${selector}'`);
            return;
        }

        elements.forEach(element => {
            element.classList.add(className);
        });
    }
    static removeClass(selector, className) {
        let elements = this.selAll(selector);

        if (!elements || elements.length === 0) {
            console.warn(`Elementos não encontrados para o seletor '${selector}'`);
            return;
        }

        elements.forEach(element => {
            element.classList.remove(className);
        });
    }
    static toggleClass(selector, className) {
        let elements = this.selAll(selector);

        if (!elements || elements.length === 0) {
            console.warn(`Elementos não encontrados para o seletor '${selector}'`);
            return;
        }

        elements.forEach(element => {
            element.classList.toggle(className);
        });
    }

    static getDataAttributes(selector) {
        const element = document.querySelector(selector);

        if (!element) {
            console.warn(`Elemento não encontrado para o seletor '${selector}'`);
            return null;
        }

        const dataAttributes = {};
        const attributeList = element.attributes;

        for (let i = 0; i < attributeList.length; i++) {
            const attribute = attributeList[i];
            if (attribute.name.startsWith('data-')) {
                const key = attribute.name.replace('data-', ''); // Remover'data-'
                dataAttributes[key] = attribute.value;
            }
        }

        return dataAttributes;
    }

    static onEventGetAttr(event, elementSelector, targetSelector, callback) {
        const element = this.sel(elementSelector);

        if (!element) {
            console.warn(`Elemento não encontrado para o seletor '${elementSelector}'`);
            return;
        }

        const defaultCallback = () => {
            return this.getDataAttributes(targetSelector);
        };

        element.addEventListener(event, (event) => {
            const targetElement = event.target.closest(targetSelector);

            if (targetElement) {
                const dataAttributes = callback ? callback(targetElement) : defaultCallback();
                console.log(dataAttributes); // Apenas para mostrar os atributos no console
            } else {
                console.warn(`Elemento não encontrado para o seletor '${targetSelector}'`);
            }
        });
    }

    static serializeForm(formId) {
        const form = document.getElementById(formId);
        if (!form || form.nodeName !== 'FORM') {
            console.error('Elemento não encontrado ou não é um formulário.');
            return null;
        }

        const formData = new FormData(form);
        const serializedData = {};

        formData.forEach((value, key) => {
            // Se já existe uma chave com esse nome, converte o valor para uma array
            if (serializedData[key]) {
                if (!Array.isArray(serializedData[key])) {
                    serializedData[key] = [serializedData[key]];
                }
                serializedData[key].push(value);
            } else {
                serializedData[key] = value;
            }
        });

        return serializedData;
    }
}

class Validators {
    static validateEmail(email) {
        return /^[\w+.]+@\w+\.\w{2,}(?:\.\w{2})?$/.test(email)
    }
    static validatePassword(password) {
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
        return regex.test(password);
    }
    static validateUser(user) {
        const hasSpecialCharacters = /[^a-zA-ZÀ-ÖØ-öø-ÿ0-9_ ]/.test(user);
        const isValidFormat = /^[a-zA-ZÀ-ÖØ-öø-ÿ0-9_ ]+$/.test(user);

        return !hasSpecialCharacters && isValidFormat;
    }
    static evaluatePasswordStrength(password) {
        if (password.length < 6) {
            return { strength: 'Fraca', message: 'Senha muito curta. Deve ter pelo menos 6 caracteres.', totalScore: 0 };
        }
    
        const lengthScore = Math.min(password.length / 8, 1);
        const uppercaseScore = /[A-Z]/.test(password) ? 1 : 0;
        const lowercaseScore = /[a-z]/.test(password) ? 1 : 0;
        const numberScore = /\d/.test(password) ? 1 : 0;
        const totalScore = lengthScore + uppercaseScore + lowercaseScore + numberScore;
    
        let strength;
        let message;
    
        if (totalScore >= 3) {
            strength = "Forte";
            message = "Senha forte!";
        } else if (totalScore === 2) {
            strength = "Média";
            message = "Senha de força média.";
        } else {
            strength = "Fraca";
            message = "Senha fraca. Tente adicionar mais caracteres e variação.";
        }
    
        return { strength, message, totalScore };
    }

    static updatePasswordStrength(password) {
        const evaluationResult = this.evaluatePasswordStrength(password);
        const progressBar = document.getElementById('password-strength-bar');
      
        if (progressBar) {
            const strengthPercentage = evaluationResult.totalScore * 33.33; // 33.33% para cada nível (fraco, médio, forte)
            
            // Adiciona uma transição suave à propriedade width
            progressBar.style.transition = 'width 0.3s ease-in-out';
            progressBar.style.width = `${strengthPercentage}%`;

            progressBar.className = 'password-strength-bar';
            if (evaluationResult.strength === 'Forte') {
                progressBar.classList.add('strong');
            } else if (evaluationResult.strength === 'Média') {
                progressBar.classList.add('medium');
            } else {
                progressBar.classList.add('weak');
            }
        } else {
            console.error('Elemento não encontrado. Verifique se há um elemento com o ID "password-strength-bar".');
        }
    }

}


// //post example 

// Helpers.request({
//     method: 'POST',
//     url: 'http://localhost/blog/create/post/',
//     data: { name: 'kerlon' },
//     success: function (data) {
//         console.log('POST request successful:', data);
//     },
//     error: function (error) {
//         console.error('POST request error:', error);
//     }
// });

// // get example

// Helpers.request({
//     method: 'GET',
//     url: 'http://localhost/blog/views/admin.php',
//     success: function (data) {
//         console.log('GET request successful:', data);
//     },
//     error: function (error) {
//         console.error('GET request error:', error);
//     }
// });