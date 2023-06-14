public class DepasseCadreException extends Exception {
	
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	int deplacementX;
	int deplacementY;
	
	DepasseCadreException(int deplacementX,int deplacementY){
		super();
		this.deplacementX=deplacementX;
		this.deplacementY=deplacementY;
	}
	
	public String toString() {
		
		String axe = null;
		String comparaison = null;
		String nb = null;
		
		if(this.deplacementX>200) {axe="abscisse"; comparaison="supérieur"; nb="200";}
		if(this.deplacementX<0) {axe="abscisse"; comparaison="inférieur"; nb="0";}
		if(this.deplacementY>200) {axe="ordonnee"; comparaison="supérieur"; nb="200";}
		if(this.deplacementY<0) {axe="abscisse"; comparaison="inférieur"; nb="0";}
		return super.toString()+" : la valeur de l'"+axe+" est "+comparaison+" à "+nb+ "!";
		}
}