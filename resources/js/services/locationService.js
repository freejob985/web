import ApiService from './api';

class LocationService {
    constructor() {
        this.api = new ApiService();
    }

    /**
     * Get all governorates
     * @returns {Promise<Array>} List of governorates
     */
    async getGovernorates() {
        try {
            const response = await this.api.request({
                url: '/api/v1/locations/governorates',
                method: 'GET'
            });
            return response.data;
        } catch (error) {
            console.error('Error fetching governorates:', error);
            throw error;
        }
    }

    /**
     * Get cities, optionally filtered by governorate
     * @param {number|null} governorateId - Optional governorate ID to filter cities
     * @returns {Promise<Array>} List of cities
     */
    async getCities(governorateId = null) {
        try {
            let url = '/api/v1/locations/cities';
            if (governorateId) {
                url += `?governorate_id=${governorateId}`;
            }
            
            const response = await this.api.request({
                url: url,
                method: 'GET'
            });
            return response.data;
        } catch (error) {
            console.error('Error fetching cities:', error);
            throw error;
        }
    }
}

export default new LocationService();