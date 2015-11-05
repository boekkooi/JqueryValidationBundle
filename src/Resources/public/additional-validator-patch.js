
/**
 * Extend the validator elements to also detect 'data-validateable' elements
 */
var validatorPrototypeElements = $.validator.prototype.elements;

$.validator.prototype.elements = function() {
    var validator = this,
        rulesCache = {};

    var elements = validatorPrototypeElements.call(this);

    var customElements = $(this.currentForm)
        .find( "*[data-validateable]" )
        .not( elements )
        .not( this.settings.ignore )
        .filter( function() {
            var name = $( this ).attr( "data-validateable" );
            if ( !name && validator.settings.debug && window.console ) {
                console.error( "%o has no `data-validateable` assigned", this );
            }

            this.form = $( this ).closest( "form" )[ 0 ];
            this.name = name;

            // Select only the first element for each name, and only those with rules specified
            if ( name in rulesCache || !validator.objectLength( $( this ).rules() ) ) {
                return false;
            }

            rulesCache[ name ] = true;
            return true;
        });

    return elements.add(customElements);
};


$.validator.prototype.idOrName = function( element ) {
    return this.groups[ element.name ] || ( this.checkable( element ) ? element.name : element.id || element.name );
};