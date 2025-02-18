import { Ref } from 'vue';

// Définition du type pour une catégorie
interface Category {
  id: number;
  name: string;
  // Ajoutez d'autres propriétés si nécessaire
}

// Définition du type pour une sous-catégorie
interface Subcategory {
  id: number;
  name: string;
  // Ajoutez d'autres propriétés si nécessaire
}

// Définition du type pour les données de catégorie
export declare interface CategoryData {
  value: Ref<Category[]>;
}

// Définition du type pour les données de sous-catégorie
export declare interface SubcategoryData {
  value: Ref<Subcategory[]>;
}

// Définition du type pour la fonction showCategory
export declare interface ShowCategory {
  (id: number): Promise<void>;
}

// Définition du type pour la fonction status
export declare interface Status {
  (): Promise<void>;
}

// Définition du type pour la fonction cat
export declare interface Cat {
  (): Promise<void>;
}

// Définition du type pour la fonction createSubcategory
export declare interface CreateSubcategory {
  (categoryData: FormData): Promise<void>;
}
